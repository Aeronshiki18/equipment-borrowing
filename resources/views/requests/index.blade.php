@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">My Borrow Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Track the status of your borrowed equipment.</p>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
            <ul class="list-disc pl-5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Table Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                        <th class="p-4 font-semibold w-1/4">Equipment</th>
                        <th class="p-4 font-semibold text-center">Qty</th>
                        <th class="p-4 font-semibold">Purpose & Dates</th>
                        <th class="p-4 font-semibold text-center">Status</th>
                        <th class="p-4 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            
                            <!-- Equipment Info -->
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    @if($req->equipment->image)
                                        <img src="{{ Storage::url($req->equipment->image) }}" alt="{{ $req->equipment->name }}" class="h-12 w-12 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-gray-900 font-medium">{{ $req->equipment->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Quantity -->
                            <td class="p-4 text-center font-bold text-gray-700">
                                {{ $req->quantity }}
                            </td>

                            <!-- Purpose & Dates -->
                            <td class="p-4">
                                <div class="text-sm text-gray-900 font-medium">{{ $req->purpose }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    
                                    <!-- SAFE DATE PARSING -->
                                    {{ $req->borrow_date ? \Carbon\Carbon::parse($req->borrow_date)->format('M d, Y') : 'N/A' }} 
                                    to 
                                    {{ $req->due_date ? \Carbon\Carbon::parse($req->due_date)->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="p-4 text-center">
                                @if($req->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">PENDING</span>
                                @elseif($req->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">APPROVED</span>
                                @elseif($req->status === 'returned')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800">RETURNED</span>
                                @elseif($req->status === 'rejected')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">REJECTED</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="p-4 text-right">
                                @if($req->status === 'approved')
                                    <form method="POST" action="{{ route('requests.return', $req->id) }}" onsubmit="return confirm('Have you physically returned this item?');" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            Return Item
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-1">No requests found</h3>
                                    <p class="text-gray-500">You haven't borrowed any equipment yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection