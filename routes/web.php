<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicRsvpController;
use Illuminate\Support\Facades\Route;

// Home Redirects to Login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Optional preview of welcome page
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Friendly Portuguese Auth Aliases and Redirects
Route::redirect('/registrar', '/register')->name('registrar');
Route::redirect('/cadastrar', '/register')->name('cadastrar');
Route::redirect('/cadastro', '/register')->name('cadastro');
Route::redirect('/cadastre-se', '/register')->name('cadastre-se');
Route::redirect('/cadastre_se', '/register');
Route::redirect('/cadastrar-se', '/register')->name('cadastrar-se');
Route::redirect('/cadastrar_se', '/register');
Route::redirect('/cadastre', '/register')->name('cadastre');
Route::redirect('/se-cadastrar', '/register')->name('se-cadastrar');
Route::redirect('/se_cadastrar', '/register');
Route::redirect('/registre-se', '/register')->name('registre-se');
Route::redirect('/registre_se', '/register');
Route::redirect('/registre', '/register')->name('registre');
Route::redirect('/registrar-se', '/register')->name('registrar-se');
Route::redirect('/registrar_se', '/register');
Route::redirect('/entrar', '/login')->name('entrar');

// Public RSVP Routes (screenshot 1 & friendly URLs)
Route::get('/convite/{slug}', [PublicRsvpController::class, 'show'])->name('public.rsvp');
Route::post('/convite/{slug}/confirmar', [PublicRsvpController::class, 'confirm'])->name('public.rsvp.confirm');
Route::get('/convite/{slug}/sucesso', [PublicRsvpController::class, 'success'])->name('public.rsvp.success');

// Support /pt/{slug} matching convide.online
Route::get('/pt/{slug}', [PublicRsvpController::class, 'show']);
Route::post('/pt/{slug}/confirmar', [PublicRsvpController::class, 'confirm']);

// Authenticated Organizer Dashboard (screenshots 2, 3, 4, 5)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('events.index');
    })->name('dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}/export', [EventController::class, 'export'])->name('events.export');

    // Guest Management
    Route::post('/events/{event}/guests', [GuestController::class, 'store'])->name('guests.store');
    Route::put('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
    Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
    Route::post('/guests/{guest}/companions', [GuestController::class, 'addCompanion'])->name('guests.companions.store');
    Route::delete('/companions/{companion}', [GuestController::class, 'destroyCompanion'])->name('companions.destroy');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
