@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 bg-white shadow rounded-lg p-6">
            <form action="{{ route('cart.checkout') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Metode Pengambilan</label>
                    <select name="pickup_method" class="mt-1 w-full border rounded px-3 py-2" required>
                        <option value="store_pickup">Ambil di Toko</option>
                        <option value="delivery">Antar ke Alamat</option>
                    </select>
                    @error('pickup_method')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat (jika antar)</label>
                    <textarea name="delivery_address" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('delivery_address') }}</textarea>
                    @error('delivery_address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="mt-3 flex items-start gap-2 rounded-lg px-3 py-2 border border-amber-400 bg-gradient-to-r from-yellow-50 via-amber-50 to-yellow-100 text-amber-900">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="mt-0.5 flex-shrink-0">
                            <path d="M12 3l9 16H3L12 3z" stroke="#f59e0b" stroke-width="2" fill="#fde68a"/>
                            <path d="M12 10v4" stroke="#92400e" stroke-width="2"/>
                            <circle cx="12" cy="17" r="1" fill="#92400e"/>
                        </svg>
                        <span class="text-sm font-medium">Pengiriman hanya menjangkau kota Batang, Pekalongan, dan Pemalang.</span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kota (jika antar)</label>
                    <select id="citySelect" name="delivery_city" class="mt-1 w-full border rounded px-3 py-2">
                        <option value="">Pilih Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ strtolower($city) }}">{{ ucfirst($city) }}</option>
                        @endforeach
                    </select>
                    @error('delivery_city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Contoh: 08xxxxxxxxxx" required>
                </div>
                <p class="text-xs text-gray-500">Ongkir otomatis per kota: Batang Rp 15.000, Pemalang Rp 20.000, Pekalongan Rp 10.000.</p>

                <div class="pt-2">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Buat Order & Lanjut Bayar</button>
                </div>
            </form>
        </div>
        <div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Ringkasan</h2>
                @foreach($cart->items as $item)
                    <div class="flex justify-between text-sm mb-2">
                        <span>{{ $item->accessory->name }} × {{ $item->quantity }}</span>
                        <span>Rp {{ number_format($item->accessory->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between font-bold text-lg border-t mt-4 pt-4">
                    <span>Total</span>
                    <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->accessory->price * $i->quantity), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script></script>
@endsection

