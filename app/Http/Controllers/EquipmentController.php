<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get active equipment
        $query = Equipment::where('status', 'active');

        // 2. Handle Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 3. Handle Category Filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // 4. Fetch data
        $equipments = $query->get();
        
        // 5. Fetch all categories for the dropdown
        $categories = Category::all();
        
        return view('equipment.index', compact('equipments', 'categories')); 
    }
}