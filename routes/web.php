<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SellerOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function (Illuminate\Http\Request $request) {
    $user = $request->user();
    $user->applyEmailDomainRole();

    if ($user->role === 'seller' && $user->status !== 'active') {
        return redirect()->route('profile.edit')->with('warning', $user->status === 'pending'
            ? 'Akun penjual Anda sedang menunggu konfirmasi admin.'
            : 'Akun penjual Anda ditolak. Silakan ajukan ulang permintaan penjual.');
    }

    $role = $user->role;
    return match($role) {
        'admin' => redirect()->route('admin.dashboard'),
        'seller' => redirect()->route('seller.dashboard'),
        'supervisor' => redirect()->route('supervisor.dashboard'),
        default => redirect()->route('user.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::get('/sellers', [\App\Http\Controllers\Admin\SellerController::class, 'index'])->name('sellers.index');
    Route::patch('/sellers/{seller}/approve', [\App\Http\Controllers\Admin\SellerController::class, 'approve'])->name('sellers.approve');
    Route::patch('/sellers/{seller}/reject', [\App\Http\Controllers\Admin\SellerController::class, 'reject'])->name('sellers.reject');

    Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');

    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    Route::patch('/transactions/{order}/approve-payment', [\App\Http\Controllers\Admin\TransactionController::class, 'approvePayment'])->name('transactions.approve-payment');
    Route::patch('/transactions/{order}/reject-payment', [\App\Http\Controllers\Admin\TransactionController::class, 'rejectPayment'])->name('transactions.reject-payment');

    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');

    Route::get('/audit-log', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-log.index');
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    
    // Seller Applications
    Route::get('/seller-applications', [\App\Http\Controllers\Admin\SellerApplicationController::class, 'index'])->name('seller-applications.index');
    Route::get('/seller-applications/{sellerApplication}', [\App\Http\Controllers\Admin\SellerApplicationController::class, 'show'])->name('seller-applications.show');
    Route::patch('/seller-applications/{sellerApplication}/approve', [\App\Http\Controllers\Admin\SellerApplicationController::class, 'approve'])->name('seller-applications.approve');
    Route::patch('/seller-applications/{sellerApplication}/reject', [\App\Http\Controllers\Admin\SellerApplicationController::class, 'reject'])->name('seller-applications.reject');
});

// Seller Routes
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class)->except(['show']);
    Route::patch('/products/{product}/toggle-status', [\App\Http\Controllers\Seller\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::get('/orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Seller\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/approve-payment', [\App\Http\Controllers\Seller\OrderController::class, 'approvePayment'])->name('orders.approve-payment');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Seller\OrderController::class, 'updateStatus'])->name('orders.update-status');
    
    // Bank Accounts Management
    Route::resource('bank-accounts', BankAccountController::class);
    
    // Orders Management (new unified controller)
    Route::get('/new-orders', [SellerOrderController::class, 'index'])->name('new-orders.index');
    Route::get('/new-orders/{order}', [SellerOrderController::class, 'show'])->name('new-orders.show');
    Route::patch('/new-orders/{order}/status', [SellerOrderController::class, 'updateStatus'])->name('new-orders.update-status');
    Route::patch('/new-orders/{order}/confirm-payment', [SellerOrderController::class, 'confirmPayment'])->name('new-orders.confirm-payment');
    
    Route::get('/revenue', [\App\Http\Controllers\Seller\RevenueController::class, 'index'])->name('revenue');
    Route::get('/scan', [\App\Http\Controllers\Seller\ScanController::class, 'index'])->name('scan');
    Route::post('/scan', [\App\Http\Controllers\Seller\ScanController::class, 'scan'])->name('scan.process');
    Route::get('/settings', [\App\Http\Controllers\Seller\SettingsController::class, 'index'])->name('settings');
    Route::patch('/settings', [\App\Http\Controllers\Seller\SettingsController::class, 'update'])->name('settings.update');
    Route::patch('/settings/toggle-closed', [\App\Http\Controllers\Seller\SettingsController::class, 'toggleClosed'])->name('settings.toggle-closed');
});

// User Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/katalog', [\App\Http\Controllers\User\CatalogController::class, 'index'])->name('catalog');
    Route::get('/produk/{product}', [\App\Http\Controllers\User\ProductController::class, 'show'])->name('product.show');
    Route::get('/keranjang', [\App\Http\Controllers\User\CartController::class, 'index'])->name('cart');
    Route::post('/keranjang/tambah', [\App\Http\Controllers\User\CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/{item}', [\App\Http\Controllers\User\CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/{item}', [\App\Http\Controllers\User\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/payment', [\App\Http\Controllers\User\CheckoutController::class, 'payment'])->name('payment');
    Route::post('/payment/clear', [\App\Http\Controllers\User\CheckoutController::class, 'clearPaymentSession'])->name('payment.clear');
    Route::post('/orders/{order}/payment-proof', [\App\Http\Controllers\User\CheckoutController::class, 'uploadProof'])->name('payment.proof');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\User\CheckoutController::class, 'invoice'])->name('invoice.show');
    Route::get('/orders/{order}/invoice/download', [\App\Http\Controllers\User\CheckoutController::class, 'downloadInvoice'])->name('invoice.download');
    
    // Orders (new unified interface)
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [UserOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [UserOrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::delete('/orders/{order}', [UserOrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}/track', [UserOrderController::class, 'trackStatus'])->name('orders.track');
    
    // Payments
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/orders/{order}/payment/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::get('/orders/{order}/payment/info', [PaymentController::class, 'bankTransferInfo'])->name('payments.bank-info');
    
    // Seller Application
    Route::get('/seller-application', [\App\Http\Controllers\User\SellerApplicationController::class, 'create'])->name('seller-application.create');
    Route::post('/seller-application', [\App\Http\Controllers\User\SellerApplicationController::class, 'store'])->name('seller-application.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/penjual', [ProfileController::class, 'sellerRequestForm'])->name('profile.seller-request');
    Route::post('/profile/penjual', [ProfileController::class, 'sellerRequestSubmit'])->name('profile.seller-request.submit');
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});
Route::post('/webhook/midtrans', [\App\Http\Controllers\Webhook\MidtransNotificationController::class, 'handle'])->name('webhook.midtrans');

require __DIR__.'/auth.php';
