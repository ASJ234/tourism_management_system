<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Tourist\TouristController;
use App\Http\Controllers\TourOperator\TourOperatorController;
use App\Http\Controllers\Tourist\BookingController;
use App\Http\Controllers\Tourist\PaymentController;
use App\Http\Controllers\Tourist\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'tourist') {
        return redirect()->route('tourist.dashboard');
    } elseif ($user->role === 'tour_operator') {
        return redirect()->route('tour_operator.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/destinations', [AdminController::class, 'destinations'])->name('destinations');
    Route::get('/destinations/create', [AdminController::class, 'createDestination'])->name('destinations.create');
    Route::post('/destinations', [AdminController::class, 'storeDestination'])->name('destinations.store');
    Route::get('/destinations/{destination}/edit', [AdminController::class, 'editDestination'])->name('destinations.edit');
    Route::put('/destinations/{destination}', [AdminController::class, 'updateDestination'])->name('destinations.update');
    Route::delete('/destinations/{destination}', [AdminController::class, 'deleteDestination'])->name('destinations.delete');
    
    // Destination Images Management
    Route::get('/destinations/images', [AdminController::class, 'allDestinationImages'])->name('destinations.all-images');
    Route::get('/destinations/{destination}/images', [AdminController::class, 'manageDestinationImages'])->name('destinations.images');
    Route::post('/destinations/{destination}/images', [AdminController::class, 'uploadDestinationImages'])->name('destinations.upload-images');
    Route::delete('/destinations/{destination}/images/{image}', [AdminController::class, 'deleteDestinationImage'])->name('destinations.delete-image');
    
    Route::get('/services', [AdminController::class, 'services'])->name('services');
    Route::get('/bookings/index', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::put('/bookings/{id}/approve', [\App\Http\Controllers\Admin\BookingController::class, 'approve'])->name('bookings.approve');
    Route::put('/bookings/{id}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// Tourist Routes
Route::middleware(['auth', 'role:tourist'])->prefix('tourist')->name('tourist.')->group(function () {
    Route::get('/dashboard', [TouristController::class, 'dashboard'])->name('dashboard');
    Route::get('/destinations', [TouristController::class, 'destinations'])->name('destinations');
    Route::get('/destinations/{destination}', [TouristController::class, 'showDestination'])->name('destinations.show');
    Route::get('/packages', [TouristController::class, 'packages'])->name('packages');
    Route::get('/packages/{package}', [TouristController::class, 'showPackage'])->name('packages.show');
    
    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}/view', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{package}/book', [BookingController::class, 'showBookingForm'])->name('bookings.book');
    
    // Reviews
    Route::get('/reviews', [TouristController::class, 'reviews'])->name('reviews');
    Route::get('/reviews/{review}/edit', [TouristController::class, 'editReview'])->name('reviews.edit');
    Route::put('/reviews/{review}', [TouristController::class, 'updateReview'])->name('reviews.update');
    Route::post('/reviews', [TouristController::class, 'storeReview'])->name('reviews.store');
    
    // Profile
    Route::get('/profile', [TouristController::class, 'profile'])->name('profile');
});

// Tour Operator Routes
Route::middleware(['auth', 'role:tour_operator'])->prefix('tour-operator')->name('tour_operator.')->group(function () {
    Route::get('/dashboard', [TourOperatorController::class, 'dashboard'])->name('dashboard');
    
    // Package Management
    Route::resource('packages', TourOperatorController::class)->except(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    Route::get('/packages', [TourOperatorController::class, 'packages'])->name('packages.index');
    Route::get('/packages/create', [TourOperatorController::class, 'createPackage'])->name('packages.create');
    Route::post('/packages', [TourOperatorController::class, 'storePackage'])->name('packages.store');
    Route::get('/packages/{package}', [TourOperatorController::class, 'showPackage'])->name('packages.show');
    Route::get('/packages/{package}/edit', [TourOperatorController::class, 'editPackage'])->name('packages.edit');
    Route::put('/packages/{package}', [TourOperatorController::class, 'updatePackage'])->name('packages.update');
    Route::delete('/packages/{package}', [TourOperatorController::class, 'destroyPackage'])->name('packages.destroy');
    
    // Booking Management
    Route::get('/bookings', [TourOperatorController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TourOperatorController::class, 'showBooking'])->name('bookings.show');
    Route::put('/bookings/{booking}/status', [TourOperatorController::class, 'updateBookingStatus'])->name('bookings.update-status');
    
    // Promotion Management
    Route::get('/promotions', [TourOperatorController::class, 'promotions'])->name('promotions.index');
    Route::get('/promotions/create', [TourOperatorController::class, 'createPromotion'])->name('promotions.create');
    Route::post('/promotions', [TourOperatorController::class, 'storePromotion'])->name('promotions.store');
    Route::put('/promotions/{package}', [TourOperatorController::class, 'updatePromotion'])->name('promotions.update');
    Route::delete('/promotions/{package}', [TourOperatorController::class, 'removePromotion'])->name('promotions.destroy');
});

Route::post('/tourist/payments', [App\Http\Controllers\Tourist\PaymentController::class, 'store'])->name('tourist.payments.store');

Route::post('/test-booking', function(\Illuminate\Http\Request $request) {
    \Log::info('Test booking received:', $request->all());
    return response()->json($request->all());
})->name('test.booking');

Route::delete('tourist/bookings/{booking_id}/cancel', [App\Http\Controllers\Tourist\BookingController::class, 'cancel'])
    ->name('tourist.bookings.cancel');
require __DIR__.'/auth.php';
