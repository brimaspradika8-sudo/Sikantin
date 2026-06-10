<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        AuditLog::create([
            'actor_id' => auth()->id(),
            'subject_id' => $product->id,
            'action' => 'delete_product',
            'description' => 'Menghapus produk ' . $product->name,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}
