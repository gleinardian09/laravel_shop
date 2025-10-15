<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// ----------------------------
// Public & Guest Routes
// ----------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/search', [SearchController::class, 'index'])->name('search');

// ----------------------------
// Authenticated User Routes
// ----------------------------
Route::middleware('auth')->group(function () {

    // Dashboard (Breeze)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
        Route::put('/update/{cartItem}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{cartItem}', [CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    });

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

    // Reviews
    Route::prefix('reviews')->group(function () {
        Route::post('/products/{product}', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Order history
    Route::prefix('my-orders')->name('orders.')->group(function () {
        Route::get('/', [OrderHistoryController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderHistoryController::class, 'show'])->name('show');
        Route::post('/{order}/reorder', [OrderHistoryController::class, 'reorder'])->name('reorder');
    });

    // Discount validation
    Route::post('/validate-discount', [DiscountController::class, 'validateDiscount'])->name('discounts.validate');

    // Mode switching
    Route::post('/switch-to-admin', [ModeController::class, 'switchToAdmin'])->name('switch.to.admin');
    Route::post('/switch-to-store', [ModeController::class, 'switchToStore'])->name('switch.to.store');
});

// ----------------------------
// Admin Routes (Manual Admin Check)
// ----------------------------
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard - FIXED: Now using the controller
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', AdminProductController::class);

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Subcategories
    Route::resource('subcategories', SubcategoryController::class);

    // Orders
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);

    // Discounts
    Route::resource('discounts', DiscountController::class);

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
});

// ----------------------------
// Authentication (Breeze)
// ----------------------------
require __DIR__.'/auth.php';