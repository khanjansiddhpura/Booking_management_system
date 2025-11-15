@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-calendar-check"></i> Edit Booking
            </h1>
            <a href="{{ route('bookings.index') }}" class="btn btn-primary" style="display: inline-block;">Create New Booking</a>
        </div>


        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bookings.update.booking', $booking->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700 font-semibold mb-2">
                    Customer Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="customer_name"
                       id="customer_name"
                       value="{{ old('customer_name', $booking->customer_name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customer_name') border-red-500 @enderror"
                       required>
                @error('customer_name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="customer_email" class="block text-gray-700 font-semibold mb-2">
                    Customer Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       name="customer_email"
                       id="customer_email"
                       value="{{ old('customer_email', $booking->customer_email) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customer_email') border-red-500 @enderror"
                       required>
                @error('customer_email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="booking_date" class="block text-gray-700 font-semibold mb-2">
                    Booking Date <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       name="booking_date"
                       id="booking_date"
                       value="{{ old('booking_date', $booking->booking_date ? $booking->booking_date->format('Y-m-d') : '') }}"
                       min="{{ date('Y-m-d') }}"
                       max="{{ date('Y-m-d', strtotime('+1 year')) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('booking_date') border-red-500 @enderror"
                       required>
                @error('booking_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="booking_type" class="block text-gray-700 font-semibold mb-2">
                    Booking Type <span class="text-red-500">*</span>
                </label>
                <select name="booking_type"
                        id="booking_type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('booking_type') border-red-500 @enderror"
                        required>
                    <option value="Full Day" {{ old('booking_type', $booking->booking_type) == 'Full Day' ? 'selected' : '' }}>Full Day</option>
                    <option value="Half Day" {{ old('booking_type', $booking->booking_type) == 'Half Day' ? 'selected' : '' }}>Half Day</option>
                    <option value="Custom" {{ old('booking_type', $booking->booking_type) == 'Custom' ? 'selected' : '' }}>Custom</option>
                </select>
                @error('booking_type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Booking Slot (only for Half Day) -->
            <div class="mb-4" id="booking_slot_container" style="display: none;">
                <label for="booking_slot" class="block text-gray-700 font-semibold mb-2">
                    Booking Slot <span class="text-red-500">*</span>
                </label>
                <select name="booking_slot"
                        id="booking_slot"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('booking_slot') border-red-500 @enderror">
                    <option value="">Select Slot</option>
                    <option value="First Half" {{ old('booking_slot', $booking->booking_slot) == 'First Half' ? 'selected' : '' }}>First Half (Morning)</option>
                    <option value="Second Half" {{ old('booking_slot', $booking->booking_slot) == 'Second Half' ? 'selected' : '' }}>Second Half (Afternoon)</option>
                </select>
                @error('booking_slot')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Custom Time (only for Custom) -->
            <div id="custom_time_container" style="display: none;">
                <div class="mb-4">
                    <label for="booking_from_time" class="block text-gray-700 font-semibold mb-2">
                        From Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           name="booking_from_time"
                           id="booking_from_time"
                           value="{{ old('booking_from_time', $booking->booking_from_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('booking_from_time') border-red-500 @enderror">
                    @error('booking_from_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="booking_to_time" class="block text-gray-700 font-semibold mb-2">
                        To Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           name="booking_to_time"
                           id="booking_to_time"
                           value="{{ old('booking_to_time', $booking->booking_to_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('booking_to_time') border-red-500 @enderror">
                    @error('booking_to_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md font-semibold hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-calendar-check"></i> Update Booking
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingType = document.getElementById('booking_type');
    const bookingSlotContainer = document.getElementById('booking_slot_container');
    const bookingSlot = document.getElementById('booking_slot');
    const customTimeContainer = document.getElementById('custom_time_container');
    const fromTime = document.getElementById('booking_from_time');
    const toTime = document.getElementById('booking_to_time');

    function toggleFields() {
        const selectedType = bookingType.value;

        // Reset all fields
        bookingSlotContainer.style.display = 'none';
        customTimeContainer.style.display = 'none';
        bookingSlot.removeAttribute('required');
        fromTime.removeAttribute('required');
        toTime.removeAttribute('required');

        // Show relevant fields based on selection
        if (selectedType === 'Half Day') {
            bookingSlotContainer.style.display = 'block';
            bookingSlot.setAttribute('required', 'required');
        } else if (selectedType === 'Custom') {
            customTimeContainer.style.display = 'block';
            fromTime.setAttribute('required', 'required');
            toTime.setAttribute('required', 'required');
        }
    }

    // Run on page load
    toggleFields();

    // Run when selection changes
    bookingType.addEventListener('change', toggleFields);
});
</script>
@endsection
