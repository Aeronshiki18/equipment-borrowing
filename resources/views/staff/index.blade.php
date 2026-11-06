@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Staff Queue</h1>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="GET" action="{{ request()->url() }}" class="flex gap-2 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by student name..." class="border p-2 rounded w-64">
        <select name="status" class="border p-2 rounded">
            <option value="">All Statuses</option>
            @foreach(['pending', 'admin_approved', 'released', 'returned', 'rejected'] as $s)
                <option value="{{ $s }}" @selected(request('status') == $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Search / Filter</button>
    </form>

    <div class="bg-white shadow rounded overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">Borrower</th>
                    <th class="p-3">Item</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $r)
                    <tr class="border-b">
                        <td class="p-3">
                            {{ $r->user->name }} <br>
                            <span class="text-xs font-bold text-gray-500 uppercase">({{ $r->user->role }})</span>
                        </td>
                        <td class="p-3 flex items-center gap-3">
                            @if($r->equipment->image)
                                <img src="{{ asset('storage/'.$r->equipment->image) }}" class="w-12 h-12 object-cover rounded">
                            @endif
                            <span>{{ $r->equipment->name }} ({{ $r->quantity }})</span>
                        </td>
                        <td class="p-3 font-bold">{{ strtoupper($r->status) }}</td>
                        <td class="p-3">
                            
                            {{-- FLOW FOR STAFF BORROWER --}}
                            @if($r->user->role === 'staff')
                                <span class="text-xs text-gray-500 italic">Handled by Admin</span>

                            {{-- FLOW FOR ADMIN BORROWER --}}
                            @elseif($r->user->role === 'admin')
                                @if($r->status === 'pending')
                                    <form method="POST" action="/staff/requests/{{ $r->id }}/approve" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Release</button>
                                    </form>
                                @endif
                                @if(in_array($r->status, ['released', 'overdue']))
                                    <form method="POST" action="/staff/requests/{{ $r->id }}/return" class="inline-flex gap-1 items-center mt-2">
                                        @csrf @method('PATCH')
                                        <select name="condition" class="border rounded text-xs p-1">
                                            <option value="good">Good</option>
                                            <option value="damaged">Damaged</option>
                                        </select>
                                        <button class="bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800">Mark Returned</button>
                                    </form>
                                @endif

                            {{-- FLOW FOR STUDENT BORROWER --}}
                            @else
                                {{-- Staff releases Student requests ONLY after Admin approval --}}
                                @if($r->status === 'admin_approved')
                                    <form method="POST" action="/staff/requests/{{ $r->id }}/approve" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Release</button>
                                    </form>
                                @endif
                                
                                {{-- Staff marks Student items as returned --}}
                                @if(in_array($r->status, ['released', 'overdue']))
                                    <form method="POST" action="/staff/requests/{{ $r->id }}/return" class="inline-flex gap-1 items-center mt-2">
                                        @csrf @method('PATCH')
                                        <select name="condition" class="border rounded text-xs p-1">
                                            <option value="good">Good</option>
                                            <option value="damaged">Damaged</option>
                                        </select>
                                        <button class="bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800">Mark Returned</button>
                                    </form>
                                @endif
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-gray-500">No requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection