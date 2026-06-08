# Midtrans Payment Setup Guide

## Konfigurasi Midtrans

### 1. Dapatkan Midtrans Credentials

1. Daftar di [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Login ke akun Anda
3. Pergi ke **Settings** → **Access Keys**
4. Copy **Server Key** dan **Client Key** untuk Sandbox mode

### 2. Update .env File

Tambahkan credentials ke file `.env`:

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxxxxxxxxxxxxxxxxxxxxx
MIDTRANS_IS_PRODUCTION=false
```

**PENTING:**
- Jangan gunakan placeholder `xxx` - harus credentials sebenarnya
- Untuk development, gunakan **Sandbox credentials** (awalan `SB-`)
- Untuk production, gunakan **Production credentials**

### 3. Session Configuration

Aplikasi ini menggunakan **file-based sessions** (`SESSION_DRIVER=file` di `.env`).
Pastikan folder `storage/framework/sessions/` writable:

```bash
chmod 775 storage/framework/sessions/
```

### 4. Verification

Untuk verify credentials sudah benar:

1. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

2. Test checkout flow:
   - Tambahkan produk ke cart
   - Lakukan checkout
   - Cek console browser (F12 → Console) untuk:
     - `Snap Token: [token-value]`
     - `Client Key: [client-key]`

### 5. Testing Midtrans Sandbox

Untuk test pembayaran di sandbox, gunakan kartu kredit demo:

**Success**: 
- Nomor: `4811111111111114`
- Exp: `12/25`
- CVV: `123`

**Declined**:
- Nomor: `4111111111111112`
- Exp: `12/25`
- CVV: `123`

## Troubleshooting

### Error: "Token pembayaran tidak tersedia"

**Penyebab mungkin:**
1. `MIDTRANS_SERVER_KEY` atau `MIDTRANS_CLIENT_KEY` kosong/placeholder
2. Session tidak berfungsi - check `storage/framework/sessions/` permissions
3. Cache config belum di-clear setelah update `.env`

**Solusi:**
```bash
php artisan config:clear
php artisan cache:clear
chmod 775 storage/framework/sessions/
```

### Snap Library Not Loading

Jika error "Snap library not loaded":
1. Check console browser untuk error scripts
2. Pastikan CDN https://app.sandbox.midtrans.com/snap/snap.js accessible
3. Untuk production, ganti ke https://app.midtrans.com/snap/snap.js

### Session tidak tersimpan

Jika session hilang setelah redirect:
1. Pastikan `SESSION_DRIVER=file` di `.env`
2. Verify folder `storage/` writable
3. Cek browser cookies terbuka

## Flow Pembayaran

1. User menambah produk → Cart
2. Checkout → `CheckoutController@process()`
3. Buat Order dan Payment record
4. Generate Snap Token via Midtrans API
5. Store token di session
6. Redirect ke halaman pembayaran
7. Load Midtrans Snap → User pilih metode bayar
8. Midtrans webhook notify backend
9. Update Order status → Redirect user

## File Penting

- `config/services.php` - Midtrans config
- `app/Services/MidtransService.php` - Midtrans API service
- `app/Http/Controllers/User/CheckoutController.php` - Checkout logic
- `resources/views/user/payment.blade.php` - Payment page
- `app/Http/Controllers/Webhook/MidtransNotificationController.php` - Webhook handler

## Links Berguna

- [Midtrans Documentation](https://docs.midtrans.com)
- [Snap Integration Guide](https://docs.midtrans.com/en/snap/overview)
- [Test Credentials](https://docs.midtrans.com/en/technical-reference/sandbox-test-credentials)
