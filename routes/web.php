<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use Illuminate\Support\Facades\Route;

// ---------- Situs publik (tanpa login) ----------
Route::get('/', [Site\HomeController::class, 'index'])->name('home');
Route::get('/bahasa/{locale}', [Site\LocaleController::class, 'switch'])->name('locale');
Route::get('/slot', [Site\BookingController::class, 'slots'])->middleware('throttle:60,1')->name('slots');
Route::post('/reservasi', [Site\BookingController::class, 'store'])->middleware('throttle:8,1')->name('booking.store');
Route::get('/reservasi/{code}', [Site\BookingController::class, 'show'])->name('booking.show');
Route::get('/cek', [Site\BookingController::class, 'lookup'])->middleware('throttle:20,1')->name('booking.lookup');
Route::post('/reservasi/{code}/batal', [Site\BookingController::class, 'cancel'])->middleware('throttle:10,1')->name('booking.cancel');
Route::get('/blog', [Site\PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{slug}', [Site\PostController::class, 'show'])->name('posts.show');

// ---------- Panel admin ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('bookings/export', [Admin\BookingController::class, 'export'])->name('bookings.export');
        Route::get('bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
        Route::patch('bookings/{booking}', [Admin\BookingController::class, 'update'])->name('bookings.update');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('staff', Admin\StaffController::class)->except('show')->parameters(['staff' => 'staff']);
        Route::put('staff/{staff}/schedule', [Admin\StaffController::class, 'saveSchedule'])->name('staff.schedule');
        Route::resource('time-offs', Admin\TimeOffController::class)->only(['index', 'store', 'destroy']);
        Route::resource('posts', Admin\PostController::class)->except('show');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
