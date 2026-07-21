<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuratPengirimanController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('customers', CustomerController::class);
Route::resource('orders', OrderController::class);
Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

Route::resource('deliveries', DeliveryController::class);
Route::resource('invoices', InvoiceController::class);
Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');

Route::resource('payments', PaymentController::class);
Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
Route::get('activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');

Route::get('pengaturan', [CompanySettingController::class, 'index'])->name('company-settings.index');
Route::post('pengaturan', [CompanySettingController::class, 'update'])->name('company-settings.update');

Route::resource('kendaraan', VehicleController::class);

Route::prefix('surat-pengiriman')->name('surat-pengiriman.')->controller(SuratPengirimanController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('{surat_pengiriman}', 'show')->name('show');
    Route::post('{surat_pengiriman}/status', 'updateStatus')->name('update-status');
    Route::post('{surat_pengiriman}/delivery', 'updateDelivery')->name('update-delivery');
    Route::post('{surat_pengiriman}/photos', 'uploadPhotos')->name('upload-photos');
    Route::get('{surat_pengiriman}/cetak', 'cetak')->name('cetak');
});
