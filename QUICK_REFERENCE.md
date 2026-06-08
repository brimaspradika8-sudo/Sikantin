# Sikantin - Quick Reference Guide

## 🚀 Mulai Cepat

### Database & Models
```bash
# Jalankan migrations
php artisan migrate --force

# Seed test data
php artisan db:seed

# Fresh migration + seed
php artisan migrate:fresh --seed
```

### Running Application
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: NPM Development
npm run dev

# Terminal 3: Queue (optional)
php artisan queue:listen

# Terminal 4: Logs
php artisan pail
```

---

## 📁 Project Structure

```
sikantin/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── OrderController.php          ✅ DONE
│   │       ├── SellerOrderController.php    ✅ DONE
│   │       ├── PaymentController.php        ✅ DONE
│   │       └── BankAccountController.php    ✅ DONE
│   ├── Models/
│   │   ├── Order.php                        ✅ DONE
│   │   ├── OrderItem.php                    ✅ DONE
│   │   ├── Payment.php                      ✅ DONE
│   │   ├── BankAccount.php                  ✅ DONE
│   │   ├── MenuItem.php                     ✅ DONE
│   │   ├── Category.php                     ✅ DONE
│   │   └── Notification.php                 ✅ DONE
│   ├── Policies/
│   │   ├── OrderPolicy.php                  ✅ DONE
│   │   └── BankAccountPolicy.php            ✅ DONE
│   └── Providers/
│       └── AuthServiceProvider.php          ✅ DONE
├── database/
│   ├── migrations/                          ✅ DONE
│   └── seeders/
│       ├── CategorySeeder.php               ✅ DONE
│       ├── MenuItemSeeder.php               ✅ DONE
│       └── BankAccountSeeder.php            ✅ DONE
├── resources/
│   └── views/
│       ├── orders/
│       │   ├── index.blade.php              ✅ DONE
│       │   ├── track.blade.php              ✅ DONE
│       │   ├── create.blade.php             📝 TODO
│       │   └── show.blade.php               📝 TODO
│       ├── seller/
│       │   ├── orders/
│       │   │   ├── index.blade.php          📝 TODO
│       │   │   └── show.blade.php           📝 TODO
│       │   └── bank-accounts/               📝 TODO
│       └── payments/                        📝 TODO
├── routes/
│   └── web.php                              ✅ DONE
└── DEVELOPMENT_GUIDE.md                     ✅ DONE
```

---

## 🎯 Key Classes & Methods

### OrderController
```php
// Customer ordering interface
OrderController::index()              // List orders
OrderController::create()             // Show order form
OrderController::store()              // Create order (transaction)
OrderController::show($order)         // View order details
OrderController::trackStatus($order)  // Real-time tracking view
```

### SellerOrderController
```php
// Seller order management
SellerOrderController::index()              // All orders dashboard
SellerOrderController::show($order)         // Order details
SellerOrderController::updateStatus()       // Change order status
SellerOrderController::confirmPayment()     // Mark COD as paid
SellerOrderController::dashboard()          // Stats dashboard
```

### PaymentController
```php
// Payment handling
PaymentController::show($order)         // Payment info
PaymentController::uploadProof()        // Upload bank transfer proof
PaymentController::verify()             // Seller verification
PaymentController::bankTransferInfo()   // Get bank details
```

### BankAccountController
```php
// Seller bank account management
BankAccountController::index()          // List accounts
BankAccountController::create()         // New account form
BankAccountController::store()          // Save account
BankAccountController::edit($account)   // Edit form
BankAccountController::update()         // Update account
BankAccountController::destroy()        // Delete account
```

---

## 🔗 Routes Reference

### Customer (User) Routes
```bash
GET    /user/orders                   # List orders
GET    /user/orders/create            # Order form
POST   /user/orders                   # Create order
GET    /user/orders/{order}           # Order detail
GET    /user/orders/{order}/track     # Track status
GET    /user/orders/{order}/payment   # Payment info
POST   /user/orders/{order}/payment/upload-proof
```

### Seller Routes
```bash
GET    /seller/new-orders             # Orders dashboard
GET    /seller/new-orders/{order}     # Order detail
PATCH  /seller/new-orders/{order}/status
PATCH  /seller/new-orders/{order}/confirm-payment
GET    /seller/bank-accounts          # Bank account list
GET    /seller/bank-accounts/create   # New account form
POST   /seller/bank-accounts          # Save account
GET    /seller/bank-accounts/{account}/edit
PATCH  /seller/bank-accounts/{account}
DELETE /seller/bank-accounts/{account}
```

---

## 💾 Common Queries

### Get Orders with Relationships
```php
// Customer orders
Order::where('user_id', auth()->id())
    ->with(['items.menuItem', 'payment', 'seller'])
    ->get();

// Seller orders (sales)
Order::where('seller_id', auth()->id())
    ->with(['user', 'items.menuItem', 'payment'])
    ->get();

// Pending payments
Order::whereHas('payment', function($q) {
    $q->where('payment_status', 'pending');
})->get();
```

### Update Order Status
```php
$order->update(['status' => 'processing']);

// Create notification
Notification::create([
    'user_id' => $order->user_id,
    'order_id' => $order->id,
    'type' => 'order_processing',
    'title' => 'Pesanan Sedang Diproses',
    'message' => "Pesanan #{$order->order_number} sedang diproses",
    'icon' => 'clock',
    'color' => 'warning',
]);
```

### Verify Payment
```php
$order->payment->update([
    'payment_status' => 'success',
    'verified_by' => auth()->id(),
    'verified_at' => now(),
]);
```

---

## 🎨 View Helpers

### Status Styling
```blade
<!-- In blade templates -->
<span class="{{ $order->statusClass() }}">
    {{ $order->statusLabel() }}
</span>

<!-- Status Classes -->
<!-- pending_payment: bg-yellow-100 text-yellow-800 -->
<!-- processing: bg-indigo-100 text-indigo-800 -->
<!-- ready: bg-sky-100 text-sky-800 -->
<!-- completed: bg-green-100 text-green-800 -->
```

### Currency Formatting
```blade
<!-- Format IDR currency -->
Rp {{ number_format($order->total_amount, 0, ',', '.') }}
```

### Date Formatting
```blade
{{ $order->created_at->format('d M Y H:i') }}
{{ $order->created_at->diffForHumans() }}
```

---

## ✅ Testing Checklist

Before deploying, test these flows:

### Customer Flow
- [ ] Browse menu items
- [ ] Create new order with multiple items
- [ ] Select payment method (COD)
- [ ] See order in list
- [ ] View order detail
- [ ] Track order status
- [ ] Receive notifications

### Payment Flow (COD)
- [ ] Order created → payment_status = pending
- [ ] Seller confirms payment → status = success
- [ ] Customer can complete order

### Payment Flow (Bank Transfer)
- [ ] Customer uploads proof → status = waiting_verification
- [ ] Seller approves → status = success
- [ ] Seller rejects → status = failed (customer can retry)

### Seller Flow
- [ ] See new orders in dashboard
- [ ] View order details
- [ ] Update order status (processing → ready → completed)
- [ ] Confirm COD payment
- [ ] Manage bank accounts

---

## 🐛 Debugging Tips

### Check Order Status
```php
php artisan tinker
> $order = Order::find(1)
> $order->status
> $order->payment
> $order->items
```

### View Notifications
```php
> $notifications = Notification::where('order_id', 1)->get()
> $notifications->each->makeVisible('*')
```

### Check Seller Sales
```php
> $seller = User::where('role', 'seller')->first()
> $seller->sales()->get()
> $seller->sales()->sum('total_amount')
```

---

## 📌 Important Notes

1. **Authorization**: Always check policies in controllers before operations
2. **Transactions**: Use DB::transaction() for multi-step operations
3. **Notifications**: Create notifications whenever order status changes
4. **Validation**: Validate all user inputs before processing
5. **Relationships**: Always eager load relationships to avoid N+1 queries

---

## 🔐 Security Reminders

✅ Always use policies (`$this->authorize()`)  
✅ Validate file uploads  
✅ Use timestamps for auditing  
✅ Hash sensitive data  
✅ Use CSRF tokens in forms  
✅ Escape output in Blade  
✅ Validate payment amounts  

---

## 📞 Useful Artisan Commands

```bash
# Database
php artisan migrate
php artisan migrate:fresh
php artisan db:seed
php artisan tinker

# Models & Controllers
php artisan make:model ModelName -m  # Create model with migration
php artisan make:controller ControllerName --resource

# Queues
php artisan queue:listen
php artisan queue:failed

# Logs
php artisan pail               # Real-time logs
php artisan log:clear

# Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

**Version**: 1.0  
**Last Updated**: {{ now()->format('d M Y H:i') }}
