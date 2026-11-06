@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    
    <!-- Header & Filters -->
    <div class="mb-10 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Available Equipment</h1>
                <p class="text-sm text-gray-500 mt-1">Browse our inventory and submit a borrow request.</p>
            </div>
            
            <!-- Search & Category Form -->
            <form method="GET" action="{{ route('equipment.index') }}" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                
                <!-- Search Input -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" placeholder="Search items..." value="{{ request('search') }}" 
                           class="pl-10 w-full sm:w-64 border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 bg-gray-50 focus:bg-white transition">
                </div>

                <!-- Category Dropdown -->
                <select name="category" class="w-full sm:w-48 border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2.5 bg-gray-50 focus:bg-white transition cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Submit Button -->
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 text-sm font-semibold transition shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Error / Success Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-sm">
            <ul class="list-disc pl-5 font-medium">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- E-Commerce Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($equipments as $equipment)
            <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col">
                
                <!-- Image Area -->
                <div class="relative h-56 bg-gray-50 overflow-hidden">
                    @if($equipment->image)
                        <img src="{{ Storage::url($equipment->image) }}" alt="{{ $equipment->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                            <svg class="w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Category Badge -->
                    @if($equipment->category)
                        <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-md text-xs font-bold text-gray-700 shadow-sm">
                            {{ $equipment->category->name }}
                        </div>
                    @endif

                    <!-- Stock Badge -->
                    <div class="absolute top-3 right-3 {{ $equipment->stock > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white px-2.5 py-1 rounded-md text-xs font-bold shadow-sm">
                        {{ $equipment->stock > 0 ? 'In Stock: '.$equipment->stock : 'Out of Stock' }}
                    </div>
                </div>

                <!-- Details Area -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 truncate">{{ $equipment->name }}</h2>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $equipment->description ?? 'No description available.' }}</p>
                    </div>

                    <!-- Action Area -->
                    <div class="mt-5">
                        @if($equipment->stock > 0)
                            <button type="button" 
                                    onclick="openBorrowModal({{ $equipment->id }}, '{{ addslashes($equipment->name) }}', {{ $equipment->stock }})"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Borrow Item
                            </button>
                        @else
                            <button disabled class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 shadow-sm text-sm font-bold rounded-xl text-gray-400 bg-gray-100 cursor-not-allowed">
                                Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex flex-col items-center justify-center">
                    <svg class="h-20 w-20 text-gray-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No equipment found</h3>
                    <p class="text-gray-500">We couldn't find anything matching your search.</p>
                    <a href="{{ route('equipment.index') }}" class="mt-4 text-blue-600 font-semibold hover:underline">Clear Filters</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ========================================== -->
<!-- BORROW MODAL (Keep as is)                  -->
<!-- ========================================== -->
<div id="borrowModal" class="hidden fixed inset-0 z-[9999] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeBorrowModal()"></div>

    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all">
            <form method="POST" action="{{ route('requests.store') }}" id="borrowForm">
                @csrf
                <input type="hidden" name="equipment_id" id="modal_equipment_id">
                
                <div class="bg-white px-6 pt-6 pb-4 border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-1" id="modal-title">Borrow Equipment</h3>
                    <p class="text-sm text-gray-500">Borrowing: <span id="modal_equipment_name" class="font-semibold text-blue-600"></span></p>
                </div>
                
                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                        <input type="number" name="quantity" id="modal_quantity" value="1" min="1" required 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <input type="text" name="purpose" required placeholder="e.g. Class Presentation" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Borrow Date</label>
                            <input type="date" name="borrow_date" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 text-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" required 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2 text-gray-600">
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit" class="inline-flex justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition">
                        Submit Request
                    </button>
                    <button type="button" onclick="closeBorrowModal()" class="inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openBorrowModal(id, name, maxStock) {
        document.getElementById('modal_equipment_id').value = id;
        document.getElementById('modal_equipment_name').innerText = name;
        const qtyInput = document.getElementById('modal_quantity');
        qtyInput.max = maxStock;
        qtyInput.value = 1; 
        document.getElementById('borrowModal').classList.remove('hidden');
    }
    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.add('hidden');
        document.getElementById('borrowForm').reset(); 
    }
</script>
@endsection