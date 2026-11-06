@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-purple-700">Admin - Manage Equipment</h1>
        <a href="/admin/equipment/create" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow">
            + Add Equipment
        </a>
    </div>

    {{-- Error Message (For Delete Restrictions) --}}
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 font-bold">{{ session('error') }}</div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($equipment as $e)
            <div class="bg-white shadow rounded flex flex-col border-l-4 border-purple-500 overflow-hidden">
                @if($e->image) 
                    <img src="{{ asset('storage/'.$e->image) }}" class="w-full h-40 object-cover"> 
                @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                @endif

                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800">{{ $e->name }}</h3>
                        <p class="text-sm text-gray-500">Code: {{ $e->code }}</p>
                        <p class="text-sm font-semibold mb-4 text-blue-600">Stock: {{ $e->total_quantity }}</p>
                    </div>
                    
                    <!-- ACTION BUTTONS (FIXED) -->
                    <div class="flex space-x-2 mt-auto">
                        <a href="/admin/equipment/{{ $e->id }}/edit" class="bg-blue-100 text-blue-600 px-3 py-1 rounded text-sm hover:bg-blue-200 flex-1 text-center font-medium">Edit</a>
                        
                        <form method="POST" action="/admin/equipment/{{ $e->id }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this equipment?')">
                            @csrf 
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-100 text-red-600 px-3 py-1 rounded text-sm hover:bg-red-200 font-medium">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $equipment->links() }}
    </div>
@endsection