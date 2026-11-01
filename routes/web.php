<?php

use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventDynamicController;
use App\Http\Controllers\EventTicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/p/{page}', [PageController::class, 'show'])->name('pages.show');
Route::get('/events/dynamic/{slug}', [EventDynamicController::class, 'show'])->name('events.dynamic');

// Event management features (ticketing, checkout, etc.) for dynamic events
Route::get('/events/dynamic/{slug}/tickets', [EventTicketController::class, 'tickets'])->name('events.dynamic.tickets');
Route::get('/events/dynamic/{slug}/checkout', [EventTicketController::class, 'checkout'])->name('events.dynamic.checkout');
Route::post('/events/dynamic/{slug}/checkout', [EventTicketController::class, 'processCheckout'])->name('events.dynamic.checkout.process');
Route::get('/events/dynamic/{slug}/thank-you', [EventTicketController::class, 'thankYou'])->name('events.dynamic.thank-you');
Route::get('/events/dynamic/{slug}/thankyou', [EventTicketController::class, 'thankYou']);
Route::get('/events/dynamic/{slug}/confirmation', [EventTicketController::class, 'confirmation'])->name('events.dynamic.confirmation');
Route::get('/events/dynamic/{slug}/download', [EventTicketController::class, 'download'])->name('events.dynamic.download');
Route::get('/events/dynamic/{slug}/tickets/download', [EventTicketController::class, 'download'])->name('events.dynamic.tickets.download');
Route::get('/events/dynamic/{slug}/pos', [EventTicketController::class, 'pos'])->name('events.dynamic.pos');
Route::get('/events/dynamic/{slug}/venue-designer', [EventTicketController::class, 'venueDesigner'])->name('events.dynamic.venue');
Route::get('/events/dynamic/{slug}/recurring', [EventTicketController::class, 'recurring'])->name('events.dynamic.recurring');
Route::get('/events/dynamic/{slug}/custom-fields', [EventTicketController::class, 'customFields'])->name('events.dynamic.fields');
Route::get('/events/dynamic/{slug}/checkin', [EventTicketController::class, 'checkin'])->name('events.dynamic.checkin');
Route::get('/events/dynamic/{slug}/calendar', [EventTicketController::class, 'calendar'])->name('events.dynamic.calendar');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('can:manage-pages')->prefix('dashboard')->group(function () {
        Route::resource('pages', AdminPageController::class)->except('show')->names('admin.pages');
    });
});
