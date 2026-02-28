<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::active()->findOrFail($request->product_id);

        if ($product->stock_quantity < 1) {
            return back()->with('error', 'This product is out of stock.');
        }

        $cart = $this->getCart();
        $id = $product->id;

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $request->quantity;
            if ($newQty > $product->stock_quantity) {
                $newQty = $product->stock_quantity;
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            $qty = min($request->quantity, $product->stock_quantity);
            $cart[$id] = [
                'id'       => $id,
                'name'     => $product->name,
                'price'    => $product->price,
                'image'    => $product->primaryImage?->path,
                'quantity' => $qty,
                'brand'    => $product->brand,
            ];
        }

        $this->saveCart($cart);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'count' => array_sum(array_column($cart, 'quantity'))]);
        }

        return back()->with('success', '"' . $product->name . '" added to cart!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = $this->getCart();
        $id = $request->product_id;

        if (isset($cart[$id])) {
            $product = Product::find($id);
            $qty = min($request->quantity, $product?->stock_quantity ?? $request->quantity);
            $cart[$id]['quantity'] = $qty;
            $this->saveCart($cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(string $productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function count()
    {
        $cart = $this->getCart();
        return response()->json(['count' => array_sum(array_column($cart, 'quantity'))]);
    }
}
