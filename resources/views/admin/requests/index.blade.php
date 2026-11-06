@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8 border-b pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Staff Borrow Requests</h1>
            <p class="text-sm text-gray-500 mt-1">Manage and approve equipment requests from staff members.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 mb-6 rounded-lg">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 mb-6 rounded-lg text-sm">
            <ul class="list-disc pl-5">@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 text-sm">
                    <th class="p-4 font-semibold">User</th>
                    <th class="p-4 font-semibold">Equipment</th>
                    <th class="p-4 font-semibold text-center">Qty</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="text-gray-900 font-medium">{{ $req->user->name }}</div>
                            <div class="text-xs text-gray-500 uppercase font-bold text-blue-600">{{ $req->user->role }}</div>
                        </td>
                        <td class="p-4 text-gray-900 font-medium">{{ $req->equipment->name }}</td>
                        <td class="p-4 text-center font-bold text-gray-700">{{ $req->quantity }}</td>
                        <td class="p-4 text-center">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full 
                                {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $req->status === 'approved' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $req->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $req->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ strtoupper($req->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2">
                                @if($req->status === 'pending')
                                    <form method="POST" action="{{ route('admin.requests.approve', $req->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded hover:bg-green-700">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.requests.reject', $req->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded hover:bg-red-700">Reject</button>
                                    </form>
                                @endif
                                @if($req->status === 'approved')
                                    <form method="POST" action="{{ route('admin.requests.return', $req->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded hover:bg-blue-700">Mark Returned</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-8 text-center text-gray-500">No staff requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection