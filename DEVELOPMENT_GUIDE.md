# Sistem Pemesanan Kantin Sikantin - Dokumentasi Lengkap

## 📋 Ringkasan Proyek

Sistem web kantin berbasis **Laravel 12** yang modern, profesional, responsif, aman, dan siap skala besar. Sistem ini memungkinkan:

- **Pelanggan** untuk memesan makanan/minuman dan melacak status pesanan secara real-time
- **Penjual** untuk mengelola pesanan masuk dan memproses pembayaran  
- **Admin** untuk melihat statistik dan laporan penjualan
- **Dua metode pembayaran**: Cash on Pickup & Transfer Bank Manual

---

## 🎯 Fitur Utama yang Diimplementasikan

### ✅ FASE 1: Database & Model (SELESAI)

#### 1. Database Schema Komprehensif
- **users** - User dengan roles (admin, seller, user)
- **orders** - Pesanan dengan status tracking
- **order_items** - Detail item pesanan
- **payments** - Info pembayaran dengan bank transfer support
- **bank_accounts** - Rekening bank penjual
- **menu_items** - Produk yang dijual
- **categories** - Kategori menu
- **order_notifications** - Tracking notifikasi pesanan

#### 2. Models dengan Relationships
```php
// Order relationships
Order::user()              // Customer
Order::seller()            // Seller
Order::items()             // OrderItems
Order::payment()           // Payment info
Order::notifications()     // Notifikasi pesanan

// Payment relationships
Payment::order()
Payment::verifier()        // Staff yang verifikasi

// User relationships
User::bankAccounts()
User::orders()             // As customer
User::sales()              // As seller
```

#### 3. Controllers Implementasi
- **OrderController** - Pemesanan pelanggan (create, index, show, track)
- **SellerOrderController** - Dashboard penjual (index, show, updateStatus, confirmPayment)
- **PaymentController** - Pembayaran (show, uploadProof, verify)
- **BankAccountController** - Manajemen rekening penjual (CRUD)

#### 4. Authorization Policies
- **OrderPolicy** - Kontrol akses pesanan (customer & seller only)
- **BankAccountPolicy** - Kontrol akses rekening (seller owner only)

#### 5. Routes Configured
```
// Customer Routes
/user/orders                     - Lihat pesanan
/user/orders/create             - Buat pesanan baru
/user/orders/{order}            - Detail pesanan
/user/orders/{order}/track      - Lacak status pesanan
/user/orders/{order}/payment    - Info pembayaran

// Seller Routes
/seller/new-orders              - Dashboard pesanan
/seller/new-orders/{order}      - Detail pesanan
/seller/new-orders/{order}/status - Update status
/seller/bank-accounts           - Kelola rekening bank
```

#### 6. Seeders dengan Test Data
- 6 kategori menu (Makanan Berat, Ringan, Minuman Panas/Dingin, Dessert, Snack)
- 12 menu items dengan harga
- 2 bank accounts untuk test seller
- Test users: admin, seller, customer, supervisor

---

## 🔐 Sistem Pembayaran

### Cash on Pickup (Bayar Saat Ambil)
```php
// Flow:
1. Customer memesan → payment_status = 'pending'
2. Seller konfirmasi pembayaran → status = 'success'
3. Customer bayar saat mengambil makanan
```

### Bank Transfer Manual
```php
// Flow:
1. Customer upload bukti transfer → status = 'waiting_verification'
2. Seller verifikasi bukti → status = 'success' atau 'failed'
3. Jika ditolak, customer upload ulang
```

---

## 📊 Status Pesanan

| Status | Deskripsi |
|--------|-----------|
| `pending_payment` | Menunggu pembayaran |
| `processing` | Sedang diproses |
| `ready` | Siap diambil |
| `completed` | Selesai |
| `cancelled` | Dibatalkan |

---

## 🎨 Frontend Views (Blade Templates)

### Customer Views
- ✅ `/resources/views/orders/index.blade.php` - Daftar pesanan dengan grid layout
- ✅ `/resources/views/orders/track.blade.php` - Tracking status dengan timeline/progress bar

### Seller Views
- 📝 `/resources/views/seller/orders/index.blade.php` - Dashboard pesanan (to be created)
- 📝 `/resources/views/seller/bank-accounts/index.blade.php` - Kelola rekening (to be created)

### Features Blade Templates
- ✅ Modern responsive design
- ✅ Dark mode support (Tailwind CSS)
- ✅ Status-based styling dengan conditional classes
- ✅ Empty states dan loading states
- ✅ Real-time status tracking dengan progress bar

---

## 🔧 Konfigurasi Keamanan

```php
// ✅ Implemented Security Features
- CSRF Protection (built-in Laravel)
- XSS Prevention (Blade escaping)
- SQL Injection Prevention (Eloquent ORM)
- Authorization Policies (roles & permissions)
- Rate Limiting middleware
- Form Validation (comprehensive)
- File upload validation
- Password hashing (bcrypt)
- HTTPS ready
- CORS configuration
```

---

## 📱 UI/UX Features

```
✅ Modern Dashboard Design
✅ Dark Mode & Light Mode Support
✅ Mobile-First Responsive Design
✅ Tailwind CSS Styling
✅ Gradient Backgrounds
✅ Card-based Layout
✅ Status-based Color Coding
✅ Empty States
✅ Smooth Transitions & Animations
✅ Progress Bars & Timeline Visualization
✅ Toast Notifications (ready for implementation)
```

---

## 🚀 Setup & Running

### 1. Clone & Setup
```bash
cd sikantin
composer install
npm install
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup
```bash
php artisan migrate --force
php artisan db:seed
```

### 3. Run Development Server
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: NPM Dev
npm run dev
```

### 4. Test Accounts
```
Admin:    admin@sikantin.com / password
Seller:   penjual@sikantin.com / password
Customer: user@sikantin.com / password
```

---

## 📊 Database Schema Summary

```sql
-- Orders Table
CREATE TABLE orders (
    id, user_id, seller_id, order_number, total_amount,
    discount_amount, tax_amount, service_fee, status,
    payment_method, estimated_ready_at, pickup_window_at,
    customer_note, created_at, updated_at
);

-- Payments Table
CREATE TABLE payments (
    id, order_id, amount, payment_status, payment_method,
    payment_channel, transaction_id, snap_token, invoice_number,
    payment_proof, bank_name, account_number, account_holder,
    raw_response, paid_at, verified_at, verified_by,
    created_at, updated_at
);

-- Bank Accounts Table
CREATE TABLE bank_accounts (
    id, user_id, bank_name, account_number, account_holder,
    is_primary, is_active, created_at, updated_at
);

-- Order Notifications Table
CREATE TABLE order_notifications (
    id, user_id, order_id, type, title, message,
    icon, color, is_read, read_at, created_at, updated_at
);

-- Menu Items Table
CREATE TABLE menu_items (
    id, seller_id, category_id, name, slug, description,
    price, stock, image, is_available, created_at, updated_at
);
```

---

## 🔄 Order Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                     CUSTOMER SIDE                            │
├─────────────────────────────────────────────────────────────┤
│  1. Browse Menu Items                                        │
│  2. Add to Cart                                              │
│  3. Checkout → Create Order                                  │
│  4. Select Payment Method:                                   │
│     - Cash on Pickup → Order ready, pay later              │
│     - Bank Transfer → Upload receipt → Wait verification    │
│  5. Track Order Status (Real-time Timeline)                 │
│  6. Receive Notifications on Status Change                  │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│                      SELLER SIDE                             │
├─────────────────────────────────────────────────────────────┤
│  1. See New Orders in Dashboard                             │
│  2. View Order Details (Items, Customer, Payment Status)   │
│  3. Update Status:                                           │
│     - Processing → Sedang Diproses                          │
│     - Ready → Siap Diambil                                  │
│     - Completed → Selesai                                   │
│  4. Handle Payments:                                         │
│     - Cash: Mark as paid when customer arrives              │
│     - Transfer: Verify bank proof                           │
│  5. View Analytics & Reports                                │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎁 Bonus Features Included

### 1. Notification System
- ✅ Order status change notifications
- ✅ Payment verification notifications
- ✅ User notification preferences

### 2. Analytics Ready
- ✅ Best-selling products tracking
- ✅ Daily revenue calculation
- ✅ Order statistics by seller

### 3. Audit Logging
- ✅ All user actions logged
- ✅ Payment verification tracking
- ✅ Order status history

### 4. API Ready
- Routes structured for easy REST API conversion
- JSON response support in controllers

---

## 📝 Next Steps (Coming Soon)

1. **Create Seller Dashboard Views**
   - Orders management table
   - Statistics cards
   - Analytics charts (Chart.js)
   - Bank account management forms

2. **Implement Real-time Features**
   - Laravel Broadcasting with Pusher/Redis
   - Real-time order updates
   - Live notification counts

3. **Add Admin Dashboard**
   - User management
   - Payment verification
   - Sales analytics
   - Revenue reports

4. **Frontend Enhancements**
   - Order creation with jQuery/Alpine
   - Cart management with Livewire
   - Search & filter functionality
   - Mobile app compatibility

5. **Testing & Optimization**
   - Unit tests
   - Integration tests
   - Performance optimization
   - Database query optimization

6. **Deployment**
   - Environment configuration
   - Cloud hosting setup
   - SSL/HTTPS configuration
   - CDN integration

---

## 🛠️ Technology Stack

```
Backend:
- Laravel 12 (PHP Framework)
- MySQL/SQLite (Database)
- Eloquent ORM

Frontend:
- Blade Templates
- Tailwind CSS 3
- Alpine.js (for interactivity)

Security:
- Laravel Breeze (Authentication)
- Spatie Permission (Roles & Permissions)
- CSRF Protection
- XSS Prevention

(Optional for Real-time):
- Laravel Broadcasting
- Pusher / Redis
- Socket.io
```

---

## 📞 Support & Contact

Untuk pertanyaan atau kontribusi, hubungi tim development.

---

## 📄 License

Proyek ini dilisensikan di bawah lisensi MIT.

---

**Status**: 🟢 **READY FOR DEVELOPMENT**  
**Last Updated**: {{ now()->format('d M Y H:i') }}
