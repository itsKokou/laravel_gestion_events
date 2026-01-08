<?php

use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\ScannerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicEventController::class, 'index'])->name('home');

Route::get('/soirees', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/soirees/{event:slug}', [PublicEventController::class, 'show'])->name('public.events.show');

Route::get('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'create'])->name('public.reservations.create');
Route::post('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'store'])->name('public.reservations.store');
Route::get('/commande/{order:order_number}', [PublicReservationController::class, 'show'])->name('public.orders.show');

Route::get('/scanner', [ScannerController::class, 'home'])->name('scanner.home');
Route::get('/scanner/{event:slug}', [ScannerController::class, 'scanPage'])->name('scanner.event');
Route::post('/scanner/{event:slug}/scan', [ScannerController::class, 'scan'])->name('scanner.scan');
