<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $shippingCost = $subtotal >= 500 ? 0 : 30; // Free shipping over 500 MAD
        $total = $subtotal + $shippingCost;

        return view('checkout.index', compact('cart', 'subtotal', 'shippingCost', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required|string|max:30',
            'address'      => 'required|string|max:500',
            'city'         => 'required|string|max:100',
            'postal_code'  => 'nullable|string|max:20',
            'notes'        => 'nullable|string|max:1000',
        ]);

        try {
            $order = DB::transaction(function () use ($request, $cart) {
                $items = [];
                $subtotal = 0;

                foreach ($cart as $productId => $item) {
                    $product = Product::lockForUpdate()->findOrFail($productId);

                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Sorry, '{$product->name}' only has {$product->stock_quantity} left in stock.");
                    }

                    $product->decrement('stock_quantity', $item['quantity']);

                    $lineTotal = $item['price'] * $item['quantity'];
                    $subtotal += $lineTotal;

                    $items[] = [
                        'product_id' => $productId,
                        'name'       => $item['name'],
                        'price'      => $item['price'],
                        'quantity'   => $item['quantity'],
                        'subtotal'   => $lineTotal,
                    ];
                }

                $shippingCost = $subtotal >= 500 ? 0 : 30;

                return Order::create([
                    'customer_name'    => $request->full_name,
                    'customer_email'   => $request->email,
                    'customer_phone'   => $request->phone,
                    'shipping_address' => [
                        'address'     => $request->address,
                        'city'        => $request->city,
                        'postal_code' => $request->postal_code,
                    ],
                    'items'          => $items,
                    'subtotal'       => $subtotal,
                    'shipping_cost'  => $shippingCost,
                    'total_amount'   => $subtotal + $shippingCost,
                    'payment_status' => 'pending',
                    'order_status'   => 'new',
                    'notes'          => $request->notes,
                ]);
            });

            // Clear cart
            session()->forget('cart');

            // Send confirmation email (silently fail if mail not configured)
            try {
                Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Log but don't fail
            }

            return redirect()->route('checkout.confirm', $order->id)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function confirm(string $orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('checkout.confirm', compact('order'));
    }
}
