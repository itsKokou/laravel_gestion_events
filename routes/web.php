<?php

use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\TicketQrController;
use App\Http\Controllers\PublicOrderInvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminControllerManagementController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicEventController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/soirees', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/soirees/{event:slug}', [PublicEventController::class, 'show'])->name('public.events.show');
// Page À propos
Route::view('/a-propos', 'public.about')->name('public.about');

Route::get('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'create'])->name('public.reservations.create');
Route::post('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'store'])->name('public.reservations.store');
Route::get('/commande/{order:order_number}', [PublicReservationController::class, 'show'])->name('public.orders.show');
Route::get('/commande/{order:order_number}/facture.pdf', [PublicOrderInvoiceController::class, 'download'])->name('public.orders.invoice');
Route::post('/commande/{order:order_number}/payer', [PaymentController::class, 'pay'])->name('public.orders.pay');

Route::get('/tickets/{ticket}/qr.svg', [TicketQrController::class, 'show'])->name('tickets.qr');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/controleurs', [AdminControllerManagementController::class, 'index'])->name('controllers.index');
    Route::get('/controleurs/nouveau', [AdminControllerManagementController::class, 'create'])->name('controllers.create');
    Route::post('/controleurs', [AdminControllerManagementController::class, 'store'])->name('controllers.store');
    Route::post('/controleurs/{user}/reset-password', [AdminControllerManagementController::class, 'resetPassword'])->name('controllers.reset_password');
    Route::delete('/controleurs/{user}', [AdminControllerManagementController::class, 'revoke'])->name('controllers.revoke');

    Route::get('/soirees', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('/soirees/nouvelle', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('/soirees', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/soirees/{event}', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/soirees/{event}', [AdminEventController::class, 'update'])->name('events.update');

    Route::get('/reservations', [AdminOrderController::class, 'index'])->name('orders.index');
});

Route::middleware(['auth', 'role:admin,controller'])->prefix('scanner')->name('scanner.')->group(function () {
    Route::get('/', [ScannerController::class, 'home'])->name('home');
    Route::get('/{event:slug}', [ScannerController::class, 'scanPage'])->name('event');
    Route::post('/{event:slug}/scan', [ScannerController::class, 'scan'])->name('scan');
});
