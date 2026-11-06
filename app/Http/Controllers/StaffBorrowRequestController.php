<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\DB;

class StaffBorrowRequestController extends Controller
{
    public function index()
    {
        // Staff only sees requests from Students
        $requests = BorrowRequest::whereHas('user', function($query) {
            $query->where('role', 'student');
        })->with(['user', 'equipment'])->latest()->get();
        
        return view('staff.requests.index', compact('requests'));
    }

    public function approve(BorrowRequest $borrowRequest)
    {
        try {
            DB::transaction(function () use ($borrowRequest) {
                if ($borrowRequest->status !== 'pending') {
                    throw new \Exception('Only pending requests can be approved.');
                }

                $equipment = Equipment::lockForUpdate()->findOrFail($borrowRequest->equipment_id);

                if ($equipment->stock < $borrowRequest->quantity) {
                    throw new \Exception('Cannot approve. Not enough stock available.');
                }

                // Deduct stock ONLY when approved
                $equipment->decrement('stock', $borrowRequest->quantity);
                $borrowRequest->update(['status' => 'approved']);
            });

            return redirect()->back()->with('success', 'Student request approved and stock deducted.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reject(BorrowRequest $borrowRequest)
    {
        try {
            if ($borrowRequest->status !== 'pending') {
                throw new \Exception('Only pending requests can be rejected.');
            }
            
            // No stock to add back because it wasn't deducted yet
            $borrowRequest->update(['status' => 'rejected']);
            return redirect()->back()->with('success', 'Student request rejected.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function markReturned(BorrowRequest $borrowRequest)
    {
        try {
            DB::transaction(function () use ($borrowRequest) {
                if ($borrowRequest->status !== 'approved') {
                    throw new \Exception('Only approved requests can be marked as returned.');
                }

                $equipment = Equipment::lockForUpdate()->findOrFail($borrowRequest->equipment_id);
                $equipment->increment('stock', $borrowRequest->quantity);
                $borrowRequest->update(['status' => 'returned']);
            });

            return redirect()->back()->with('success', 'Item returned. Stock added back!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}