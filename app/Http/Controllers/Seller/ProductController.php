<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Vendor;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)->get();
        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $allowedCategoryNames = ['Camilan', 'Makanan Berat', 'Minuman'];
        $categories = Category::whereIn('name', $allowedCategoryNames)->get();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $allowedCategoryNames = ['Camilan', 'Makanan Berat', 'Minuman'];
        $allowedCategoryIds = Category::whereIn('name', $allowedCategoryNames)->pluck('id')->toArray();

        $request->validate([
            'category_id' => ['required', Rule::in($allowedCategoryIds)],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('product-images', 'public');

        Product::create([
            'user_id' => $request->user()->id,
            'vendor_id' => optional(Vendor::where('user_id', $request->user()->id)->first())->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);

        $allowedCategoryNames = ['Camilan', 'Makanan Berat', 'Minuman'];
        $categories = Category::whereIn('name', $allowedCategoryNames)->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);

        $allowedCategoryNames = ['Camilan', 'Makanan Berat', 'Minuman'];
        $allowedCategoryIds = Category::whereIn('name', $allowedCategoryNames)->pluck('id')->toArray();

        $request->validate([
            'category_id' => ['required', Rule::in($allowedCategoryIds)],
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-images', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);

        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleStatus(Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);

        $product->update(['is_open' => !$product->is_open]);

        $status = $product->is_open ? 'dijual' : 'ditandai habis';
        return redirect()->route('seller.products.index')->with('success', "Produk berhasil $status.");
    }
}
