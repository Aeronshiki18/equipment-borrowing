@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-end mb-8 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Equipment</h1>
            <p class="text-sm text-gray-500 mt-1">Create a new item in the inventory.</p>
        </div>
        <a href="{{ route('admin.equipment.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
            &larr; Cancel and go back
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-lg text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.equipment.store') }}" enctype="multipart/form-data" class="p-6 md:p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Equipment Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                               class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition sm:text-sm py-2.5">
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition sm:text-sm py-2.5">
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Initial Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0" 
                               class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition sm:text-sm py-2.5">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Item Status</label>
                        <div class="flex gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="active" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Active</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="status" value="inactive" class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Inactive</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <textarea name="description" id="description" rows="4" 
                                  class="w-full rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition sm:text-sm p-3"
                                  placeholder="Enter equipment details...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- NEW IMAGE UPLOAD LAYOUT (Full Width) -->
                <div class="md:col-span-2 mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Equipment Image</label>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-4 border border-gray-200 rounded-xl bg-gray-50">
                        
                        <!-- Image Preview Area -->
                        <div class="relative w-40 h-40 flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-200 flex items-center justify-center overflow-hidden">
                            <!-- Placeholder Icon (Hidden when image is selected) -->
                            <div id="image-placeholder" class="text-gray-400 flex flex-col items-center">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs">No Image</span>
                            </div>
                            
                            <!-- Image Tag (Initially hidden) -->
                            <img id="image-preview" src="#" alt="Preview" class="hidden w-full h-full object-cover">
                        </div>

                        <!-- Upload Controls -->
                        <div class="flex-1 w-full text-center sm:text-left">
                            <p class="text-sm font-medium text-gray-900 mb-1">Upload a product photo</p>
                            <p class="text-xs text-gray-500 mb-4">PNG, JPG or GIF. Max size of 2MB. High resolution looks best.</p>
                            
                            <label for="image" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 cursor-pointer transition">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Browse Files
                                <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg shadow-sm text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    Save Equipment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT FOR IMAGE PREVIEW -->
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image-preview');
            output.src = reader.result;
            output.classList.remove('hidden');
            
            var placeholder = document.getElementById('image-placeholder');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection