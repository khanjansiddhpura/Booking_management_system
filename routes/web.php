<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('bookings.index');
    }
    return view('welcome');
});

// Login and Signup Routes
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Booking Routes
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my-bookings');
    Route::get('/my-bookings/data', [BookingController::class, 'getMyBookingsData'])->name('bookings.my-bookings.data');
    Route::get('/bookings/{id}/edit', [BookingController::class, 'editBooking'])->name('bookings.edit');
    Route::post('/bookings/{id}/edit', [BookingController::class, 'updatebooking'])->name('bookings.update.booking');
    Route::delete('/bookings/{id}', [BookingController::class, 'deleteBooking'])->name('bookings.destroy');
    Route::get('/bookings/{id}/show', [BookingController::class, 'showBooking'])->name('bookings.show');
});
