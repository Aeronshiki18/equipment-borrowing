@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Submit Borrow Request</h1>
        
        <form method="POST" action="/requests">
            @csrf
            
            <div class="mb-4">
                <label class="block font-medium mb-1">Select Equipment</label>
                <select name="equipment_id" class="border p-2 w-full rounded">
                    @foreach($equipment as $e) 
                        <option value="{{ $e->id }}" @selected(request('equipment_id')==$e->id)>{{ $e->name }} (Stock: {{ $e->total_quantity }})</option> 
                    @endforeach
                </select>
                @error('equipment_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label class="block font-medium mb-1">Quantity</label>
                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" class="border p-2 w-full rounded">
                @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-medium mb-1">Borrow Date</label>
                    <input type="date" name="borrow_date" value="{{ old('borrow_date') }}" class="border p-2 w-full rounded">
                    @error('borrow_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-medium mb-1">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="border p-2 w-full rounded">
                    @error('due_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Purpose</label>
                <textarea name="purpose" rows="3" class="border p-2 w-full rounded">{{ old('purpose') }}</textarea>
                @error('purpose') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- ILAGAY ANG RECAPTCHA DITO, BAGO ANG SUBMIT BUTTON -->
            <div class="mb-6">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                @error('g-recaptcha-response') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded w-full hover:bg-green-700 font-bold">Submit Request</button>
        </form>
    </div>

    <!-- ILAGAY ANG SCRIPT SA PINAKABABA, LABAS NG FORM -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection