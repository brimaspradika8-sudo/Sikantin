<?php

$dir = __DIR__;

// 1. Seller ProductController
$productControllerPath = $dir . '/app/Http/Controllers/Seller/ProductController.php';
$productControllerContent = <<<'EOT'
<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)->get();
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);

        Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }
}
EOT;
file_put_contents($productControllerPath, $productControllerContent);

// 2. Views
@mkdir($dir . '/resources/views/seller/products', 0777, true);
$indexView = <<<'EOT'
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <i class="fa-solid fa-box text-yellow-500 mr-2"></i> {{ __('Kelola Produk') }}
            </h2>
            <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">+ Tambah Produk</a>
        </div>
    </x-slot>

    <div class="py-12" data-aos="fade-up">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="p-3">Nama Produk</th>
                                <th class="p-3">Harga</th>
                                <th class="p-3">Stok</th>
                                <th class="p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="p-3">{{ $product->name }}</td>
                                <td class="p-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="p-3">{{ $product->stock }}</td>
                                <td class="p-3">
                                    <button class="text-blue-500 hover:underline">Edit</button> | 
                                    <button class="text-red-500 hover:underline">Hapus</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center text-gray-500">Belum ada produk.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
EOT;
file_put_contents($dir . '/resources/views/seller/products/index.blade.php', $indexView);

// 3. Web Routes
$webPath = $dir . '/routes/web.php';
$webContent = file_get_contents($webPath);
$routeInject = <<<'EOT'
// Seller Routes
Route::middleware(['auth', 'verified', 'role:seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', function () { return view('seller.dashboard'); })->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Seller\ProductController::class);
});
EOT;
$webContent = preg_replace('/\/\/ Seller Routes.*?(?=\/\/ Supervisor Routes)/s', $routeInject . "\n\n", $webContent);
file_put_contents($webPath, $webContent);

echo "Phase 3 generated!";
