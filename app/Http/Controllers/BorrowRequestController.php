<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowRequestController extends Controller
{
    public function index()
    {
        $requests = BorrowRequest::where('user_id', auth()->id())->with('equipment')->latest()->get();
        return view('requests.index', compact('requests'));
    }

    public function create()
    {
        $equipments = Equipment::where('status', 'active')->where('stock', '>', 0)->get();
        return view('requests.create', compact('equipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
            'purpose' => 'required|string',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:borrow_date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $equipment = Equipment::lockForUpdate()->findOrFail($request->equipment_id);

                // Check stock, but DO NOT deduct it yet. Wait for Admin approval.
                if ($equipment->stock < $request->quantity) {
                    throw new \Exception('Not enough stock available to request this amount.');
                }

                // Create the request
                BorrowRequest::create([
                    'user_id' => auth()->id(),
                    'equipment_id' => $equipment->id,
                    'quantity' => $request->quantity,
                    'purpose' => $request->purpose,
                    'borrow_date' => $request->borrow_date,
                    'due_date' => $request->due_date,
                    'status' => 'pending', 
                ]);
            });

            return redirect()->route('requests.index')->with('success', 'Request submitted successfully! Waiting for admin approval.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}