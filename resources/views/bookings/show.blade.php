@extends('layouts.app')

@section('title', 'Show Booking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-calendar-check"></i> Booking Details
            </h1>
            <a href="{{ route('bookings.my-bookings') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition duration-200">
                <i class="fas fa-arrow-left"></i> Back to My Bookings
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Customer Name</label>
                <p class="text-lg text-gray-800">{{ $booking->customer_name }}</p>
            </div>

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Customer Email</label>
                <p class="text-lg text-gray-800">{{ $booking->customer_email }}</p>
            </div>

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Booking Date</label>
                <p class="text-lg text-gray-800">
                    <i class="fas fa-calendar-day text-indigo-600"></i>
                    {{ $booking->booking_date ? $booking->booking_date->format('F d, Y') : 'N/A' }}
                </p>
            </div>

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Booking Type</label>
                <p class="text-lg text-gray-800">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $booking->booking_type }}
                    </span>
                </p>
            </div>

            @if($booking->booking_type === 'Half Day' && $booking->booking_slot)
            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Booking Slot</label>
                <p class="text-lg text-gray-800">
                    <i class="fas fa-clock text-indigo-600"></i>
                    {{ $booking->booking_slot }}
                </p>
            </div>
            @endif

            @if($booking->booking_type === 'Custom' && ($booking->booking_from_time || $booking->booking_to_time))
            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">From Time</label>
                <p class="text-lg text-gray-800">
                    <i class="fas fa-clock text-indigo-600"></i>
                    {{ $booking->booking_from_time ?? 'N/A' }}
                </p>
            </div>

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">To Time</label>
                <p class="text-lg text-gray-800">
                    <i class="fas fa-clock text-indigo-600"></i>
                    {{ $booking->booking_to_time ?? 'N/A' }}
                </p>
            </div>
            @endif

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Created At</label>
                <p class="text-lg text-gray-800">{{ $booking->created_at->format('F d, Y h:i A') }}</p>
            </div>

            <div class="border-b pb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Last Updated</label>
                <p class="text-lg text-gray-800">{{ $booking->updated_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mt-8 flex gap-4">
            <a href="{{ route('bookings.edit', $booking->id) }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 transition duration-200">
                <i class="fas fa-edit"></i> Edit Booking
            </a>
            <a href="{{ route('bookings.my-bookings') }}" class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition duration-200">
                <i class="fas fa-list"></i> View All Bookings
            </a>
        </div>
    </div>
</div>
@endsection
