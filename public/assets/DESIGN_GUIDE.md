# Sikantin Design System

## 📁 Lokasi Assets

Semua files SVG dan dokumentasi design tersedia di:
```
/public/assets/
```

## 🎨 Logo

**File:** `logo.svg`

Logo Sikantin menggabungkan:
- 🛍️ **Shopping Bag** - Melambangkan e-commerce/marketplace
- 🏪 **Store Building** - Melambangkan toko/seller
- **Warna:** Orange (#F97316) - Energik, modern, dan friendly

**Penggunaan:**
```html
<img src="/assets/logo.svg" alt="Sikantin" width="80" height="80">
```

**Ukuran Rekomendasi:**
- Logo type: 160px - 240px
- Favicon: 32px × 32px
- Header: 60px - 80px
- Social media: 200px × 200px

## 📦 Icon Set

### Available Icons:

| Icon | File | Penggunaan |
|------|------|-----------|
| 🏪 Store | `icon-store.svg` | Menu seller/toko |
| 📦 Product | `icon-product.svg` | List produk, katalog |
| 💳 Payment | `icon-payment.svg` | Pembayaran, transaksi |
| 📊 Inventory | `icon-inventory.svg` | Stock/stok produk |
| 👤 Seller | `icon-seller.svg` | Profile penjual |
| 📮 Order | `icon-order.svg` | Pesanan/order |
| ⭐ Rating | `icon-rating.svg` | Review/rating |
| 📈 Revenue | `icon-revenue.svg` | Pendapatan/analytics |
| 🔲 QR Code | `icon-qrcode.svg` | Pickup QR code |

### Cara Menggunakan:

```html
<!-- Sebagai IMG -->
<img src="/assets/icon-store.svg" alt="Store" width="24" height="24">

<!-- Sebagai INLINE SVG -->
<svg class="w-6 h-6 text-orange-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <!-- content -->
</svg>

<!-- Dengan Tailwind CSS -->
<img src="/assets/icon-store.svg" alt="Store" class="w-6 h-6 text-orange-500">
```

### Icon Properties:

- **Stroke Width:** 2px (consistent)
- **Stroke Linecap:** round
- **Stroke Linejoin:** round
- **Viewbox:** 24×24
- **Scalable:** 16px - 64px optimal range

### Styling dengan CSS:

```css
.icon-primary {
    color: #F97316; /* Primary orange */
}

.icon-secondary {
    color: #667eea; /* Secondary purple */
}

.icon-success {
    color: #10b981; /* Success green */
}

.icon-danger {
    color: #ef4444; /* Danger red */
}
```

## 🎨 Palet Warna

### Primary Colors:

| Warna | Hex | Usage |
|-------|-----|-------|
| Orange | `#F97316` | Buttons, highlights, primary CTA |
| Purple | `#667eea` | Secondary, hover states |
| Deep Purple | `#764ba2` | Accent, gradients |

### Status Colors:

| Status | Hex | Penggunaan |
|--------|-----|-----------|
| Success | `#10b981` | Completed, active |
| Warning | `#f59e0b` | Pending, caution |
| Danger | `#ef4444` | Error, sold out |
| Info | `#3b82f6` | Information |

### Neutral:

| Level | Hex |
|-------|-----|
| Dark | `#1f2937` |
| Gray | `#6b7280` |
| Light | `#f3f4f6` |
| White | `#ffffff` |

## 📝 Typography

### Font Stack:

```css
font-family: 'Segoe UI', 'Helvetica Neue', 'Poppins', sans-serif;
```

### Sizes:

- **H1:** 2.25rem (36px) - Bold 700
- **H2:** 1.875rem (30px) - Semibold 600
- **H3:** 1.5rem (24px) - Semibold 600
- **Body:** 1rem (16px) - Regular 400
- **Small:** 0.875rem (14px) - Regular 400
- **Tiny:** 0.75rem (12px) - Regular 400

## 🎭 Dark Mode

Icons dan logo support dark mode dengan CSS custom properties:

```css
.dark {
    color-scheme: dark;
}

.dark img[src*=".svg"] {
    filter: invert(0.95) brightness(1.1);
}
```

## 🔍 Branding Rules

1. **Logo Clearance:** Minimal 40px clearance dari edges
2. **Color Consistency:** Selalu gunakan primary orange (#F97316) untuk brand recognition
3. **Icon Size:** Jangan scale icons di bawah 16px, maksimal 64px
4. **Responsive:** Icons harus responsive dan scalable
5. **Accessibility:** Semua icons harus memiliki alt text atau aria-label

## 📱 Implementation Examples

### React Component:

```jsx
// Icon Component
export const Icon = ({ type = 'store', size = 24, className = '' }) => {
  const icons = {
    store: 'icon-store.svg',
    product: 'icon-product.svg',
    payment: 'icon-payment.svg',
    order: 'icon-order.svg',
  };

  return (
    <img 
      src={`/assets/${icons[type]}`} 
      alt={type}
      width={size}
      height={size}
      className={className}
    />
  );
};
```

### Blade Template:

```blade
<!-- Logo -->
<img src="{{ asset('assets/logo.svg') }}" alt="Sikantin" width="80" height="80">

<!-- Icon -->
<img src="{{ asset('assets/icon-store.svg') }}" alt="Store" class="w-6 h-6 text-orange-500">
```

## 🎨 Design Preview

Kunjungi: `http://localhost/assets/index.html` untuk melihat showcase lengkap logo dan icons.

## 📄 License

Semua assets (logo dan icons) adalah milik Sikantin dan dapat digunakan untuk keperluan project.

## 🚀 Best Practices

1. **Always use SVG** untuk scalability dan smaller file size
2. **Cache icons** di browser dengan proper cache headers
3. **Optimize SVG** menggunakan tools seperti SVGO
4. **Use semantic HTML** dengan proper alt attributes
5. **Test contrast** untuk accessibility (WCAG AA standard)
6. **Responsive sizing** - use viewport-relative units (%, vw, vh)

## 📞 Support

Untuk pertanyaan atau request design baru, hubungi tim development.
