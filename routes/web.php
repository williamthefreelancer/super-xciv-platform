<?php

use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityMembershipController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\ConvoyController;
use App\Http\Controllers\ConvoyMembershipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TravelRouteController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::view('/operators', 'operators')->name('operators');
Route::view('/about', 'about')->name('about');
Route::get('/p/{page}', [PageController::class, 'show'])->name('pages.show');

Route::get('/gatherings', [EventController::class, 'index'])->name('events.index');
Route::get('/communities', [CommunityController::class, 'index'])->name('communities.index');
Route::get('/broadcasts', [BroadcastController::class, 'index'])->name('broadcasts.index');
Route::get('/routes', [TravelRouteController::class, 'index'])->name('routes.index');
Route::get('/convoys', [ConvoyController::class, 'index'])->name('convoys.index');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/gatherings/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/gatherings', [EventController::class, 'store'])->name('events.store');
    Route::post('/gatherings/{event}/registration', [EventRegistrationController::class, 'store'])->name('events.register');
    Route::delete('/gatherings/{event}/registration', [EventRegistrationController::class, 'destroy'])->name('events.unregister');

    Route::get('/communities/create', [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities', [CommunityController::class, 'store'])->name('communities.store');
    Route::post('/communities/{community}/membership', [CommunityMembershipController::class, 'store'])->name('communities.join');
    Route::delete('/communities/{community}/membership', [CommunityMembershipController::class, 'destroy'])->name('communities.leave');
    Route::post('/communities/{community}/posts', [CommunityPostController::class, 'store'])->name('communities.posts.store');
    Route::delete('/communities/{community}/posts/{post}', [CommunityPostController::class, 'destroy'])->name('communities.posts.destroy');

    Route::get('/broadcasts/create', [BroadcastController::class, 'create'])->name('broadcasts.create');
    Route::post('/broadcasts', [BroadcastController::class, 'store'])->name('broadcasts.store');
    Route::post('/broadcasts/{broadcast}/live', [BroadcastController::class, 'live'])->name('broadcasts.live');
    Route::post('/broadcasts/{broadcast}/end', [BroadcastController::class, 'end'])->name('broadcasts.end');

    Route::get('/routes/create', [TravelRouteController::class, 'create'])->name('routes.create');
    Route::post('/routes', [TravelRouteController::class, 'store'])->name('routes.store');

    Route::get('/convoys/create', [ConvoyController::class, 'create'])->name('convoys.create');
    Route::post('/convoys', [ConvoyController::class, 'store'])->name('convoys.store');
    Route::post('/convoys/{convoy}/membership', [ConvoyMembershipController::class, 'store'])->name('convoys.join');
    Route::delete('/convoys/{convoy}/membership', [ConvoyMembershipController::class, 'destroy'])->name('convoys.leave');

    Route::middleware('can:manage-pages')->prefix('dashboard')->group(function () {
        Route::resource('pages', AdminPageController::class)->except('show')->names('admin.pages');
    });
});

Route::get('/gatherings/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
Route::get('/broadcasts/{broadcast}', [BroadcastController::class, 'show'])->name('broadcasts.show');
Route::get('/broadcasts/{broadcast}/status', [BroadcastController::class, 'status'])->name('broadcasts.status');
Route::get('/broadcasts/{broadcast}/token', [BroadcastController::class, 'token'])->name('broadcasts.token');
Route::get('/routes/{travelRoute}', [TravelRouteController::class, 'show'])->name('routes.show');
Route::get('/convoys/{convoy}', [ConvoyController::class, 'show'])->name('convoys.show');
