@extends('layouts.app')
@section('content')
    <div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
        <h1 class="text-xl font-bold mb-4 text-purple-700">Add New Equipment</h1>
        <form method="POST" action="/admin/equipment" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label class="block font-medium text-sm mb-1">Equipment Name</label><input type="text" name="name" class="border p-2 w-full rounded" required></div>
            <div class="mb-3"><label class="block font-medium text-sm mb-1">Equipment Code</label><input type="text" name="code" class="border p-2 w-full rounded" required></div>
            <div class="mb-3">
                <label class="block font-medium text-sm mb-1">Category</label>
                <select name="category_id" class="border p-2 w-full rounded" required>
                    @foreach($categories as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                </select>
            </div>
            <div class="mb-3"><label class="block font-medium text-sm mb-1">Total Quantity</label><input type="number" name="total_quantity" value="1" min="1" class="border p-2 w-full rounded" required></div>
            <div class="mb-4"><label class="block font-medium text-sm mb-1">Equipment Image (Optional)</label><input type="file" name="image" class="border p-2 w-full rounded"></div>
            <div class="flex justify-between">
                <a href="/admin/equipment" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
                <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded">Save Equipment</button>
            </div>
        </form>
    </div>
@endsection