@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Accessory checkout</p>
                <h1 class="section-title">Buat order aksesoris</h1>
                <p class="section-copy">Item di cart akan dikunci menjadi order, lalu kamu lanjut upload bukti pembayaran.</p>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Kembali ke cart
            </a>
        </div>

        @if(session('error'))
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
        @endif

        <form action="{{ route('cart.checkout') }}" method="POST" class="grid gap-6 lg:grid-cols-[1fr_360px]">
            @csrf

            <section class="card p-6">
                <div class="mb-5">
                    <h2 class="text-lg font-semibold text-slate-950">Pengambilan</h2>
                    <p class="mt-1 text-sm text-slate-600">Pilih ambil di toko atau antar ke kota yang tersedia.</p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Metode</label>
                        <select name="pickup_method" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="store_pickup" {{ old('pickup_method') === 'store_pickup' ? 'selected' : '' }}>Ambil di toko</option>
                            <option value="delivery" {{ old('pickup_method') === 'delivery' ? 'selected' : '' }}>Antar ke alamat</option>
                        </select>
                        @error('pickup_method')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Alamat pengiriman</label>
                        <textarea name="delivery_address" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" rows="3">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Kota pengiriman</label>
                            <select id="citySelect" name="delivery_city" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
                                <option value="">Pilih kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ strtolower($city) }}" {{ old('delivery_city') === strtolower($city) ? 'selected' : '' }}>{{ ucfirst($city) }}</option>
                                @endforeach
                            </select>
                            @error('delivery_city')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Nomor HP</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()->phone) }}" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" placeholder="08xxxxxxxxxx" required>
                            @error('phone_number')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                        Pengiriman hanya untuk Batang, Pekalongan, dan Pemalang. Ongkir otomatis dihitung saat order dibuat.
                    </div>
                </div>
            </section>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <h2 class="text-lg font-semibold text-slate-950">Ringkasan</h2>
                <div class="mt-5 space-y-4">
                    @foreach($cart->items as $item)
                        <div class="flex items-start justify-between gap-4 text-sm">
                            <div>
                                <div class="font-semibold text-slate-950">{{ $item->accessory->name }}</div>
                                <div class="mt-1 text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->accessory->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="font-semibold text-slate-950">Rp {{ number_format($item->accessory->price * $item->quantity, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 border-t border-slate-200 pt-5">
                    <div class="flex items-center justify-between gap-4 text-lg font-bold text-slate-950">
                        <span>Total barang</span>
                        <span>Rp {{ number_format($cart->items->sum(fn($i) => $i->accessory->price * $i->quantity), 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Setelah checkout, cart kosong karena item sudah pindah ke order ini.</p>
                </div>

                <button type="submit" class="btn btn-primary mt-5 w-full">
                    <i class="fas fa-credit-card mr-2" aria-hidden="true"></i>
                    Buat order & bayar
                </button>
            </aside>
        </form>
    </div>
</div>
@endsection
