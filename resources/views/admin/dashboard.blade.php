@extends('layouts.app')
@section('content')
    <h1 class="text-2xl font-bold mb-6">System Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-600 text-white p-4 rounded shadow text-center">
            <h3 class="font-medium opacity-80">Total Requests</h3>
            <p class="text-4xl font-bold">{{ $total }}</p>
        </div>
        @foreach(['pending', 'approved', 'released'] as $status)
            <div class="bg-white p-4 rounded shadow text-center border-t-4 border-blue-500">
                <h3 class="font-medium text-gray-500 uppercase text-sm">{{ $status }}</h3>
                <p class="text-3xl font-bold">{{ $counts[$status] ?? 0 }}</p>
            </div>
        @endforeach
    </div>
@endsection