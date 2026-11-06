@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">System Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-blue-600 text-white p-4 rounded shadow text-center">
            <h3 class="font-medium opacity-80">Total Requests</h3>
            <p class="text-4xl font-bold">{{ $total }}</p>
        </div>
        @foreach(['pending', 'admin_approved', 'released'] as $status)
            <div class="bg-white p-4 rounded shadow text-center border-t-4 border-blue-500">
                <h3 class="font-medium text-gray-500 uppercase text-sm">{{ str_replace('_', ' ', $status) }}</h3>
                <p class="text-3xl font-bold">{{ $counts[$status] ?? 0 }}</p>
            </div>
        @endforeach
    </div>

    <!-- CHART SECTION -->
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="font-bold text-lg mb-4">Monthly Borrow Requests</h2>
        <canvas id="monthlyChart" height="100"></canvas>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($monthly->pluck('month')),
                datasets: [{
                    label: 'Number of Requests',
                    data: @json($monthly->pluck('total')),
                    backgroundColor: 'rgba(59, 130, 246, 0.5)', // Blue color
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>
@endsection