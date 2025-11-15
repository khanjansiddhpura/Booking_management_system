@extends('layouts.app')

@section('title', 'Welcome to Booking Management System')

@section('content')
<div class="max-w-4xl mx-auto text-center px-4">
    <div class="mb-8">
        <h1 class="text-5xl font-bold text-gray-800 mb-4">
            <i class="fas fa-calendar-check text-indigo-600"></i>
            Booking Management System
        </h1>
        <p class="text-xl text-gray-600">
            Manage your bookings efficiently with our easy-to-use platform
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-4xl text-indigo-600 mb-4">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">User Management</h3>
            <p class="text-gray-600">Secure authentication system with sign up and login functionality</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-4xl text-indigo-600 mb-4">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Flexible Booking</h3>
            <p class="text-gray-600">Full day, half day, and custom time slot booking options</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="text-4xl text-indigo-600 mb-4">
                <i class="fas fa-tasks"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Complete CRUD</h3>
            <p class="text-gray-600">Create, view, edit, and delete bookings with ease</p>
        </div>
    </div>

    <div class="mt-12 space-x-4">
        <a href="{{ route('signup') }}"
           class="inline-block bg-indigo-600 text-white px-8 py-4 rounded-md text-lg font-semibold hover:bg-indigo-700 transition duration-200">
            <i class="fas fa-user-plus"></i> Get Started
        </a>
        <a href="{{ route('login') }}"
           class="inline-block bg-gray-600 text-white px-8 py-4 rounded-md text-lg font-semibold hover:bg-gray-700 transition duration-200">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
    </div>

</div>
@endsection
