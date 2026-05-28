@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if($cart->items->isEmpty())
        <div class="text-center py-12">
            <p class="text-xl font-semibold text-slate-700">Cart kosong.</p>
            <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">Kalau kamu baru checkout, item aksesoris sudah pindah menjadi order dan bisa dicek di My Orders.</p>
            <div class="mt-5 flex flex-wrap justify-center gap-3">
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">My Orders</a>
                <a href="{{ route('products.accessories') }}" class="btn btn-primary">Cari aksesoris</a>
            </div>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Product</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantity</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart->items as $item)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-20 h-20">
                                        <img class="w-full h-full rounded-md" src="{{ $item->accessory->image ? asset('storage/' . $item->accessory->image) : 'https://via.placeholder.com/150' }}" alt="{{ $item->accessory->name }}"/>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-900 whitespace-no-wrap">{{ $item->accessory->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">Rp {{ number_format($item->accessory->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 text-center border-gray-300 rounded-md">
                                    <button type="submit" class="ml-2 text-sky-700 hover:text-sky-900"><i class="fas fa-sync-alt"></i></button>
                                </form>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">Rp {{ number_format($item->accessory->price * $item->quantity, 0, ',', '.') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <form action="{{ route('cart.remove', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-end">
            <div class="w-full md:w-1/3">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Cart Total</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($cart->items->sum(function($item) { return $item->accessory->price * $item->quantity; }), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-4">
                        <span>Total</span>
                        <span>Rp {{ number_format($cart->items->sum(function($item) { return $item->accessory->price * $item->quantity; }), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex gap-3 mt-6">
                        <form method="POST" action="{{ route('cart.empty') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-md font-semibold hover:bg-gray-300">Empty Cart</button>
                        </form>
                        <a href="{{ route('cart.checkout.form') }}" class="btn btn-primary flex-1">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
