<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

// === HALAMAN DEPAN (USER) ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/articles/{slug}', [\App\Http\Controllers\ArticlePublicController::class, 'show'])->name('articles.show');

Route::get('/flowchart', function () {
    return view('flowcharts.system');
})->name('flowchart');

// === DASHBOARD (ROLE-AWARE) ===
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user) {
        // Jika ada method isAdmin pada model User gunakan itu, jika tidak cek field role
        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? 'user') === 'admin');
        if ($isAdmin) {
            return app(\App\Http\Controllers\Admin\DashboardController::class)->index();
        }
    }
    return app(\App\Http\Controllers\UserDashboardController::class)->index();
})->middleware('auth')->name('dashboard');

// Track Order
use App\Http\Controllers\TransactionStatusController;
Route::get('/track', [TransactionStatusController::class, 'track'])->name('transaction.track');
Route::post('/track', [TransactionStatusController::class, 'track'])->name('transaction.track.post');

// === INVOICE ===
use App\Http\Controllers\InvoiceController;
Route::middleware('auth')->group(function () {
    Route::get('/invoice/{transaction}', [InvoiceController::class, 'show'])->name('invoice.show');
});

// === PRODUK ===
use App\Http\Controllers\ProductController;
Route::get('/playstation', [ProductController::class, 'playstation'])->name('products.playstation');
Route::get('/accessories', [ProductController::class, 'accessories'])->name('products.accessories');

// === CART & ORDER (USER) ===
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserRentalController;
use App\Http\Controllers\RentalReminderController;
use App\Http\Controllers\OrderIssueController;

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/checkout', [CartController::class, 'checkoutForm'])->name('cart.checkout.form');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment', [OrderController::class, 'payment'])->name('orders.payment');
    Route::patch('/orders/{order}/payment', [OrderController::class, 'processPayment'])->name('orders.payment.process');
    Route::get('/my-issues', [OrderIssueController::class, 'index'])->name('orders.issues.index');

    Route::get('/rent/{type}', [UserRentalController::class, 'create'])->name('rent.create');
    Route::post('/rent/{type}', [UserRentalController::class, 'store'])->name('rent.store');
    Route::get('/my-rentals', [UserRentalController::class, 'index'])->name('user.rentals.index');
    Route::get('/my-rentals/{rental}', [UserRentalController::class, 'show'])->name('user.rentals.show');
    Route::get('/rentals/{rental}/payment', [UserRentalController::class, 'payment'])->name('rentals.payment');
    Route::patch('/rentals/{rental}/payment', [UserRentalController::class, 'processPayment'])->name('rentals.payment.process');
    Route::get('/orders/{order}/issue', [OrderIssueController::class, 'create'])->name('orders.issue.create');
    Route::post('/orders/{order}/issue', [OrderIssueController::class, 'store'])->name('orders.issue.store');
});

// === PROFILE (USER) ===
use App\Http\Controllers\ProfileController;
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === ADMIN PANEL ===
use App\Http\Controllers\ReportController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/rentals/reminders/send', [RentalReminderController::class, 'send'])->name('rentals.reminders.send');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/sessions/active', [\App\Http\Controllers\Admin\DashboardController::class, 'activeSessions'])
        ->name('admin.sessions.active');
    Route::resource('admin-users', \App\Http\Controllers\Admin\AdminUserController::class)
        ->middleware('role:owner')
        ->only(['index', 'create', 'store', 'destroy'])
        ->names([
            'index' => 'admin.admin-users.index',
            'create' => 'admin.admin-users.create',
            'store' => 'admin.admin-users.store',
            'destroy' => 'admin.admin-users.destroy',
        ]);

    Route::resource('playstation-types', \App\Http\Controllers\Admin\PlaystationTypeController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.playstation-types.index',
            'create' => 'admin.playstation-types.create',
            'store' => 'admin.playstation-types.store',
            'edit' => 'admin.playstation-types.edit',
            'update' => 'admin.playstation-types.update',
            'destroy' => 'admin.playstation-types.destroy',
        ]);

    Route::resource('playstation-units', \App\Http\Controllers\Admin\PlaystationUnitController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.playstation-units.index',
            'create' => 'admin.playstation-units.create',
            'store' => 'admin.playstation-units.store',
            'edit' => 'admin.playstation-units.edit',
            'update' => 'admin.playstation-units.update',
            'destroy' => 'admin.playstation-units.destroy',
        ]);

    Route::resource('accessories', \App\Http\Controllers\Admin\AccessoryController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.accessories.index',
            'create' => 'admin.accessories.create',
            'store' => 'admin.accessories.store',
            'edit' => 'admin.accessories.edit',
            'update' => 'admin.accessories.update',
            'destroy' => 'admin.accessories.destroy',
        ]);

    Route::resource('rentals', \App\Http\Controllers\Admin\RentalController::class)
        ->names([
            'index' => 'admin.rentals.index',
            'create' => 'admin.rentals.create',
            'store' => 'admin.rentals.store',
            'show' => 'admin.rentals.show',
            'edit' => 'admin.rentals.edit',
            'update' => 'admin.rentals.update',
            'destroy' => 'admin.rentals.destroy',
        ]);

    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])
        ->name('admin.orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])
        ->name('admin.orders.show');
    Route::patch('/orders/{order}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirmPayment'])
        ->name('admin.orders.confirm');
    Route::patch('/orders/{order}/reject', [\App\Http\Controllers\Admin\OrderController::class, 'rejectPayment'])
        ->name('admin.orders.reject');
    Route::patch('/orders/{order}/fulfillment', [\App\Http\Controllers\Admin\OrderController::class, 'updateFulfillment'])
        ->name('admin.orders.fulfillment');
    Route::patch('/orders/{order}/tracking', [\App\Http\Controllers\Admin\OrderController::class, 'updateTracking'])
        ->name('admin.orders.tracking');
    Route::patch('/rentals/{rental}/confirm', [\App\Http\Controllers\Admin\RentalController::class, 'confirmPayment'])
        ->name('admin.rentals.confirm');
    Route::patch('/rentals/{rental}/reject', [\App\Http\Controllers\Admin\RentalController::class, 'rejectPayment'])
        ->name('admin.rentals.reject');
    Route::patch('/rentals/{rental}/fulfillment', [\App\Http\Controllers\Admin\RentalController::class, 'updateFulfillment'])
        ->name('admin.rentals.fulfillment');
    Route::patch('/rentals/{rental}/set-active', [\App\Http\Controllers\Admin\RentalController::class, 'setActive'])
        ->name('admin.rentals.set-active');
    Route::patch('/rentals/{rental}/set-completed', [\App\Http\Controllers\Admin\RentalController::class, 'setCompleted'])
        ->name('admin.rentals.set-completed');

    Route::get('/order-issues', [\App\Http\Controllers\Admin\OrderIssueController::class, 'index'])
        ->name('admin.order-issues.index');
    Route::get('/order-issues/{issue}', [\App\Http\Controllers\Admin\OrderIssueController::class, 'show'])
        ->name('admin.order-issues.show');
    Route::patch('/order-issues/{issue}/respond', [\App\Http\Controllers\Admin\OrderIssueController::class, 'respond'])
        ->name('admin.order-issues.respond');

    // Articles
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class)
        ->except(['show'])
        ->names([
            'index' => 'admin.articles.index',
            'create' => 'admin.articles.create',
            'store' => 'admin.articles.store',
            'edit' => 'admin.articles.edit',
            'update' => 'admin.articles.update',
            'destroy' => 'admin.articles.destroy',
        ]);

    Route::resource('shipping', \App\Http\Controllers\Admin\ShippingRateController::class)
        ->except(['show'])
        ->parameters(['shipping' => 'shipping'])
        ->names([
            'index' => 'admin.shipping.index',
            'create' => 'admin.shipping.create',
            'store' => 'admin.shipping.store',
            'edit' => 'admin.shipping.edit',
            'update' => 'admin.shipping.update',
            'destroy' => 'admin.shipping.destroy',
        ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/shipping/rates', [\App\Http\Controllers\ShippingRateApiController::class, 'list'])->name('shipping.rates');
    Route::get('/api/distance', [\App\Http\Controllers\GeocodeController::class, 'distance'])->name('api.distance');
});

// === LOGIN (PAKAI LARAVEL BREEZE / FORTIFY) ===
// Kalau pakai Breeze, cukup ini:
require __DIR__.'/auth.php';
