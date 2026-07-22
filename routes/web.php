<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController as UserAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Partner\AuthController as PartnerAuthController;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Partner\EventController as PartnerEventController;

// Rute Admin Area
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {

        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    });

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth:admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('scanner', [\App\Http\Controllers\CheckinController::class, 'adminScanner'])->name('scanner');
        Route::post('scanner/verify', [\App\Http\Controllers\CheckinController::class, 'verifyAdmin'])->name('scanner.verify');

        Route::resource('events', EventAdminController::class)->only(['index', 'edit', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        Route::get('transactions', [TransactionController::class, 'index'])
            ->name('transactions.index');
    });
});

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);

// Rute User Area
Route::get('login', [UserAuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('login', [UserAuthController::class, 'login'])->middleware('guest');
Route::post('logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('auth/google', [UserAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [UserAuthController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::post('/tickets/{transaction}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');

Route::middleware('auth')->group(function () {
    Route::get('/my-ticket', [TicketController::class, 'index'])->name('ticket');
    Route::get('/my-ticket/{transaction:order_id}', [TicketController::class, 'show'])->name('ticket.show');
});

Route::get('/bantuan', [HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/katalog', [HomeController::class, 'katalog'])->name('katalog');
Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

// Rute Partner Area
Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('login', [PartnerAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [PartnerAuthController::class, 'login']);
    Route::get('register', [PartnerAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [PartnerAuthController::class, 'register']);
    Route::post('logout', [PartnerAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth:partner', 'partner.active'])->group(function () {
        Route::get('dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('scanner', [\App\Http\Controllers\CheckinController::class, 'partnerScanner'])->name('scanner');
        Route::post('scanner/verify', [\App\Http\Controllers\CheckinController::class, 'verifyPartner'])->name('scanner.verify');
        Route::resource('events', PartnerEventController::class)->except(['show']);
    });
});

Route::get('/partner/{partner}', [EventController::class, 'partnerProfile'])
    ->name('partner.profile')
    ->whereNumber('partner');