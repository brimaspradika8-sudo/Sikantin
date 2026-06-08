<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('products')->get();

        $query = Product::with('seller', 'category')
            ->where('is_open', true)
            ->whereHas('seller', function ($q) {
                $q->where('is_closed', false);
            });

        if ($request->filled('q')) {
            $query->where(function ($sub) use ($request) {
                $sub->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('description', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'latest' => $query->latest(),
                default => $query->orderBy('name'),
            };
        } else {
            $query->latest();
        }

        $products = $query->get();

        return view('user.catalog', compact('products', 'categories'));
    }
}