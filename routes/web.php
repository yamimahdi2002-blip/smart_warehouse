<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseStockController;
use Illuminate\Support\Facades\Route;

// روت‌های احراز هویت (مهمان)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// روت‌های نیازمند ورود (کاربر لاگین شده)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // مدیریت انبارها
    Route::resource('warehouses', WarehouseController::class);
    // مدیریت کاربران (تعریف انباردار، ناظر مالی، مدیر)
    Route::resource('users', UserController::class);

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/barcode', [ProductController::class, 'printBarcode'])->name('products.barcode');

    Route::resource('suppliers', SupplierController::class);
    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update', 'destroy']);
    Route::get('/stocks', [WarehouseStockController::class, 'index'])->name('stocks.index');

    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');
});
