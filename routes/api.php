<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\Lapangan;
use App\Http\Controllers\Api\LapanganController;
use App\Http\Controllers\Api\Login;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\User;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\Api\AuthenticateApiToken;
use Illuminate\Support\Facades\Route;

Route::post('/users', [AuthController::class, 'register'])->middleware('throttle:5,1')->name('api.users.register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('api.login');
Route::get('/users/check-email', [UserController::class, 'checkEmail'])->middleware('throttle:10,1')->name('api.users.check-email');

Route::middleware([AuthenticateApiToken::class, 'throttle:120,1'])->group(function (): void {
    Route::get('/bookings', [BookingController::class, 'index'])->name('api.bookings.index');
    Route::get('/bookings/lapangan/{lapangan}', [BookingController::class, 'lapangan'])->whereNumber('lapangan')->name('api.bookings.lapangan');
    Route::get('/bookings/{kodeBooking}', [BookingController::class, 'show'])->where('kodeBooking', '[A-Za-z0-9-]+')->name('api.bookings.show');
    Route::get('/users', [UserController::class, 'index'])->name('api.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->whereNumber('user')->name('api.users.show');
    Route::get('/me', [AuthController::class, 'me'])->name('api.me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('api.profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('api.profile.update');
});

Route::get('/lapangan', [LapanganController::class, 'index'])->middleware('throttle:60,1')->name('api.lapangan.index');
Route::get('/lapangan/{lapangan}', [LapanganController::class, 'show'])->whereNumber('lapangan')->middleware('throttle:60,1')->name('api.lapangan.show');
Route::get('/booking-lapangan', [LapanganController::class, 'fromBookings'])->middleware('throttle:60,1')->name('api.booking-lapangan.index');

Route::get('/user', [User::class, 'index'])->middleware('throttle:10,1')->name('api.user');
Route::get('/Login', [Login::class, 'index'])->name('api.login');
Route::get('/Lapangan', [Lapangan::class, 'index'])->name('api.lapangan');
