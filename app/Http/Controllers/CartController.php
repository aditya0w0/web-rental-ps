<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart()->with('items.accessory')->firstOrCreate(['user_id' => Auth::id()]);
        return view('cart.index', compact('cart'));
    }

    public function checkoutForm()
    {
        $cart = Auth::user()->cart()->with('items.accessory')->firstOrCreate(['user_id' => Auth::id()]);
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $cities = config('service.allowed_cities');
        $rates = config('service.shipping_rates');
        return view('cart.checkout', compact('cart', 'cities', 'rates'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'accessory_id' => 'required|exists:accessories,id',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $cart = Auth::user()->cart()->firstOrCreate(['user_id' => Auth::id()]);
        $accessory = Accessory::findOrFail($request->accessory_id);
        $quantity = $request->input('quantity', 1);

        if ($accessory->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock for ' . $accessory->name);
        }

        $cartItem = $cart->items()->where('accessory_id', $accessory->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            $cart->items()->create([
                'accessory_id' => $accessory->id,
                'quantity' => $quantity,
            ]);
        }

        $accessory->decrement('stock', $quantity);

        return redirect()->route('cart.index')->with('success', 'Accessory added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $this->authorize('update', $item);

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $accessory = $item->accessory;
        $newQuantity = $request->quantity;
        $oldQuantity = $item->quantity;

        if ($accessory->stock + $oldQuantity < $newQuantity) {
            return redirect()->back()->with('error', 'Not enough stock for ' . $accessory->name);
        }

        $item->update(['quantity' => $newQuantity]);

        $quantityDifference = $newQuantity - $oldQuantity;
        $accessory->decrement('stock', $quantityDifference);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(CartItem $item)
    {
        $this->authorize('delete', $item);

        $accessory = $item->accessory;
        $quantity = $item->quantity;

        $item->delete();

        $accessory->increment('stock', $quantity);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $cart = Auth::user()->cart;

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'pickup_method' => 'required|in:store_pickup,delivery',
            'delivery_address' => 'nullable|string|required_if:pickup_method,delivery',
            'delivery_city' => 'nullable|string|required_if:pickup_method,delivery',
            'phone_number' => 'required|string',
        ]);

        if ($data['pickup_method'] === 'delivery') {
            $allowed = collect(config('service.allowed_cities'));
            if (!$allowed->contains(strtolower($data['delivery_city']))) {
                return back()->withInput()->with('error', 'Pengiriman hanya tersedia untuk Pemalang, Batang, dan Pekalongan.');
            }
        }

        $shipping = 0;
        if ($data['pickup_method'] === 'delivery') {
            $city = strtolower($data['delivery_city']);
            $map = [
                'batang' => 15000,
                'pemalang' => 20000,
                'pekalongan' => 10000,
            ];
            $shipping = (float) ($map[$city] ?? 0);
        }

        // simpan nomor HP ke profil user
        if (!empty($data['phone_number'])) {
            Auth::user()->update(['phone' => $data['phone_number']]);
        }

        $order = Auth::user()->orders()->create([
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'total_price' => $cart->items->sum(function ($item) {
                return $item->quantity * $item->accessory->price;
            }) + $shipping,
            'shipping_cost' => $shipping,
            'pickup_method' => $data['pickup_method'],
            'delivery_address' => $data['pickup_method'] === 'delivery' ? ($data['delivery_address'] ?? null) : null,
            'delivery_city' => $data['pickup_method'] === 'delivery' ? strtolower($data['delivery_city']) : null,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'accessory_id' => $item->accessory_id,
                'quantity' => $item->quantity,
                'price' => $item->accessory->price,
            ]);
        }

        $cart->items()->delete();

        return redirect()->route('orders.payment', $order)->with('success', 'Checkout successful. Please proceed with payment.');
    }

    public function empty()
    {
        $cart = Auth::user()->cart()->with('items.accessory')->first();
        if (!$cart) return redirect()->route('cart.index');
        foreach ($cart->items as $item) {
            $item->accessory->increment('stock', $item->quantity);
        }
        $cart->items()->delete();
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }
}
