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
        $items = $cart->items()->with('product')->get();
        return view('user.cart', compact('cart', 'items'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);
        $quantity = max(1, $request->input('quantity', 1));
        $existing = CartItem::where('cart_id', $cart->id)->where('product_id', $request->product_id)->first();
        $newQuantity = $existing ? $existing->quantity + $quantity : $quantity;

        // stock feature removed — allow adding any quantity

        if ($existing) {
            $existing->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create(['cart_id' => $cart->id, 'product_id' => $request->product_id, 'quantity' => $quantity]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, CartItem $item)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($item->cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $product = $item->product;
        // stock checks removed — accept requested quantity

        $item->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah keranjang diperbarui.');
    }

    public function remove(Request $request, CartItem $item)
    {
        if ($item->cart->user_id !== $request->user()->id) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}