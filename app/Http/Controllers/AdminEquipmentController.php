<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- ADDED FOR IMAGE HANDLING

class AdminEquipmentController extends Controller
{
    public function index()
    {
        $equipments = Equipment::with('category')->latest()->get();
        return view('admin.equipment.index', compact('equipments'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.equipment.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for image
        ]);

        $data = $request->all();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('equipment', 'public');
            $data['image'] = $path;
        }

        Equipment::create($data);

        return redirect()->route('admin.equipment.index')->with('success', 'Equipment added successfully!');
    }

    public function edit($id)
    {
        $equipment = Equipment::findOrFail($id);
        $categories = Category::all();
        return view('admin.equipment.edit', compact('equipment', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'status' => 'required|string|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for image
        ]);

        $data = $request->all();

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($equipment->image);
            }
            // Store new image
            $path = $request->file('image')->store('equipment', 'public');
            $data['image'] = $path;
        }

        $equipment->update($data);

        return redirect()->route('admin.equipment.index')->with('success', 'Equipment updated successfully!');
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);
        
        // Delete image file when deleting the equipment
        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();
        return redirect()->route('admin.equipment.index')->with('success', 'Equipment deleted successfully!');
    }
}