<?php

use App\Http\Controllers\Admin\AdminControllerManagementController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\PublicOrderInvoiceController;
use App\Http\Controllers\PublicReservationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\TicketQrController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicEventController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::get('/soirees', [PublicEventController::class, 'index'])->name('public.events.index');
Route::get('/soirees/{event:slug}', [PublicEventController::class, 'show'])->name('public.events.show');
// Page À propos
Route::view('/a-propos', 'public.about')->name('public.about');

Route::view('/contact', 'public.contact')->name('public.contact');

Route::get('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'create'])->name('public.reservations.create');
Route::post('/soirees/{event:slug}/reserver', [PublicReservationController::class, 'store'])->name('public.reservations.store');
Route::get('/commande/{order:order_number}', [PublicReservationController::class, 'show'])->name('public.orders.show');
Route::get('/commande/{order:order_number}/paiement', [PaymentController::class, 'checkout'])->name('public.orders.checkout');
Route::get('/commande/{order:order_number}/facture.pdf', [PublicOrderInvoiceController::class, 'download'])->name('public.orders.invoice');
Route::post('/commande/{order:order_number}/payer', [PaymentController::class, 'pay'])->name('public.orders.pay');

Route::get('/tickets/{ticket}/qr.svg', [TicketQrController::class, 'show'])->name('tickets.qr');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard', [AdminDashboardController::class, 'globalStats'])->name('dashboard.data');
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'globalStats'])->name('dashboard.stats');
    Route::get('/events/{id}/stats', [AdminDashboardController::class, 'eventStats'])->whereNumber('id')->name('events.stats');
    Route::get('/events/{id}/conversion', [AdminDashboardController::class, 'conversion'])->whereNumber('id')->name('events.conversion');
    Route::get('/analytics/revenue', [AdminDashboardController::class, 'revenue'])->name('analytics.revenue');
    Route::get('/analytics/tickets', [AdminDashboardController::class, 'tickets'])->name('analytics.tickets');

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
    Route::post('/soirees/{event}/archiver', [AdminEventController::class, 'archive'])->name('events.archive');

    Route::get('/reservations/export', [AdminOrderController::class, 'export'])->name('orders.export');
    Route::get('/reservations', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::post('/reservations/{order:order_number}/annuler', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/reservations/{order:order_number}/tickets/{ticket}/annuler', [AdminOrderController::class, 'cancelTicket'])->name('orders.tickets.cancel');
    Route::get('/reservations/{order:order_number}', [AdminOrderController::class, 'show'])->name('orders.show');

    Route::get('/billets', [AdminTicketController::class, 'index'])->name('tickets.index');
});

Route::middleware(['auth', 'role:admin,controller'])->prefix('scanner')->name('scanner.')->group(function () {
    Route::get('/', [ScannerController::class, 'home'])->name('home');
    Route::get('/{event:slug}', [ScannerController::class, 'scanPage'])->name('event');
    Route::post('/{event:slug}/scan', [ScannerController::class, 'scan'])->name('scan');
    Route::get('/{event:slug}/scan/{token}', [ScannerController::class, 'scanFromUrl'])->name('scan.url');
});
