@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto mt-10">
        <h1 class="text-xl font-bold mb-4 text-purple-700">Edit Equipment</h1>
        
        <form method="POST" action="/admin/equipment/{{ $equipment->id }}" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Importante ito para maging Update imbes na Create -->
            
            <div class="mb-3">
                <label class="block font-medium text-sm mb-1">Equipment Name</label>
                <input type="text" name="name" value="{{ old('name', $equipment->name) }}" class="border p-2 w-full rounded" required>
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="block font-medium text-sm mb-1">Equipment Code</label>
                <input type="text" value="{{ $equipment->code }}" class="border p-2 w-full rounded bg-gray-100 cursor-not-allowed" readonly>
                <small class="text-gray-500">Equipment code cannot be changed.</small>
            </div>

            <div class="mb-3">
                <label class="block font-medium text-sm mb-1">Total Quantity</label>
                <input type="number" name="total_quantity" value="{{ old('total_quantity', $equipment->total_quantity) }}" min="1" class="border p-2 w-full rounded" required>
                @error('total_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm mb-1">Update Image (Optional)</label>
                
                <!-- Ipakita ang kasalukuyang image kung meron -->
                @if($equipment->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/'.$equipment->image) }}" class="w-32 h-32 object-cover rounded">
                    </div>
                @endif

                <input type="file" name="image" class="border p-2 w-full rounded">
                <small class="text-gray-500">Leave blank to keep the current image.</small>
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-between mt-6">
                <a href="/admin/equipment" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 font-bold">Update Equipment</button>
            </div>
        </form>
    </div>
@endsection