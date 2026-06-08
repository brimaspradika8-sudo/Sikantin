<?php

$dir = __DIR__;

// 1. Catalog Controller
$catalogControllerPath = $dir . '/app/Http/Controllers/User/CatalogController.php';
$catalogControllerContent = <<<'EOT'
<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $products = Product::with('seller', 'category')->get();
        return view('user.catalog', compact('products'));
    }
}
EOT;
file_put_contents($catalogControllerPath, $catalogControllerContent);

// 2. Cart Controller
$cartControllerPath = $dir . '/app/Http/Controllers/User/CartController.php';
$cartControllerContent = <<<'EOT'
<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $items = CartItem::with('product')->where('cart_id', $cart->id)->get();
        return view('user.cart', compact('cart', 'items'));
    }

    public function add(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $item = CartItem::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();
        if($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'quantity' => 1]);
        }
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }
}
EOT;
file_put_contents($cartControllerPath, $cartControllerContent);

// 3. Views
$catalogView = <<<'EOT'
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fa-solid fa-utensils text-yellow-500 mr-2"></i> {{ __('Katalog SiKantin') }}
            </h2>
            <a href="{{ route('user.cart') }}" class="text-yellow-600 hover:text-yellow-700">
                <i class="fa-solid fa-cart-shopping fa-lg"></i> Keranjang
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg" data-aos="fade-up">
                    <div class="h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <i class="fa-solid fa-image text-gray-400 text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $product->seller->store_name ?? 'Kantin' }}</p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-yellow-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <form action="{{ route('user.cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm shadow">
                                    + Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg">
                    Belum ada produk yang dijual saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
EOT;
file_put_contents($dir . '/resources/views/user/catalog.blade.php', $catalogView);

$cartView = <<<'EOT'
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <i class="fa-solid fa-cart-shopping text-yellow-500 mr-2"></i> {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="p-3">Produk</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Jumlah</th>
                                <th class="p-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @forelse($items as $item)
                            @php $subtotal = $item->quantity * $item->product->price; $total += $subtotal; @endphp
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="p-3">{{ $item->product->name }}</td>
                                <td class="p-3">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td class="p-3">{{ $item->quantity }}</td>
                                <td class="p-3 font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">Keranjang masih kosong.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if($items->count() > 0)
                        <tfoot>
                            <tr>
                                <td colspan="3" class="p-3 text-right font-bold text-lg">Total Pembayaran:</td>
                                <td class="p-3 font-bold text-lg text-yellow-600">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>

                    @if($items->count() > 0)
                    <div class="mt-6 flex justify-end">
                        <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg">
                            Lanjut Pembayaran <i class="fa-solid fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
EOT;
file_put_contents($dir . '/resources/views/user/cart.blade.php', $cartView);

// 4. Update routes
$webPath = $dir . '/routes/web.php';
$webContent = file_get_contents($webPath);
$routeInject = <<<'EOT'
// User Routes
Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () { return view('user.dashboard'); })->name('dashboard');
    Route::get('/katalog', [\App\Http\Controllers\User\CatalogController::class, 'index'])->name('catalog');
    Route::get('/keranjang', [\App\Http\Controllers\User\CartController::class, 'index'])->name('cart');
    Route::post('/keranjang/tambah', [\App\Http\Controllers\User\CartController::class, 'add'])->name('cart.add');
});
EOT;
$webContent = preg_replace('/\/\/ User Routes.*?\}\);/s', $routeInject, $webContent);
file_put_contents($webPath, $webContent);

// Add default product for testing
\App\Models\Product::create([
    'user_id' => 2, // Penjual
    'name' => 'Nasi Goreng Spesial',
    'slug' => 'nasi-goreng-spesial',
    'description' => 'Nasi goreng dengan telur, sosis, dan ayam suwir.',
    'price' => 15000,
    'stock' => 20
]);

\App\Models\Product::create([
    'user_id' => 2, // Penjual
    'name' => 'Es Teh Manis',
    'slug' => 'es-teh-manis',
    'description' => 'Es teh manis segar.',
    'price' => 4000,
    'stock' => 50
]);

echo "Phase 3 User Catalog & Cart Generated!";
