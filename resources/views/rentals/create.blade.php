@extends('layouts.app')

@section('title', 'Checkout Rental - ' . $type->name)

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Rental checkout</p>
                <h1 class="section-title">Sewa {{ $type->name }}</h1>
                <p class="section-copy">Pilih jadwal, metode pengambilan, dan tambahkan aksesoris sebelum lanjut pembayaran.</p>
            </div>
            <a href="{{ route('products.playstation') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('rent.store', $type) }}" class="grid gap-6 lg:grid-cols-[1fr_340px]">
            @csrf

            <div class="space-y-6">
                <section class="card p-6">
                    <div class="mb-5">
                        <h2 class="text-lg font-semibold text-slate-950">Detail rental</h2>
                        <p class="mt-1 text-sm text-slate-600">{{ $availableUnits }} unit tersedia.</p>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Mulai</label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                            @error('start_time')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-[1fr_140px] gap-3">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Durasi</label>
                                <input type="number" min="1" name="duration_value" value="{{ old('duration_value', 1) }}" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                                @error('duration_value')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Tipe</label>
                                <select name="duration_type" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                                    <option value="hour" {{ old('duration_type') === 'hour' ? 'selected' : '' }}>Jam</option>
                                    <option value="day" {{ old('duration_type') === 'day' ? 'selected' : '' }}>Hari</option>
                                </select>
                                @error('duration_type')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card p-6">
                    <div class="mb-5">
                        <h2 class="text-lg font-semibold text-slate-950">Pengambilan</h2>
                        <p class="mt-1 text-sm text-slate-600">Pengiriman hanya untuk Batang, Pekalongan, dan Pemalang.</p>
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
                                <select name="delivery_city" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
                                    <option value="">Pilih kota</option>
                                    @foreach(config('service.allowed_cities') as $city)
                                        <option value="{{ strtolower($city) }}" {{ old('delivery_city') === strtolower($city) ? 'selected' : '' }}>{{ ucfirst($city) }}</option>
                                    @endforeach
                                </select>
                                @error('delivery_city')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Nomor HP</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()->phone) }}" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                                @error('phone_number')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                            Denda keterlambatan Rp {{ number_format(config('service.late_fee_per_hour'), 0, ',', '.') }} per jam. KTP diserahkan saat unit diterima atau diambil.
                        </div>
                    </div>
                </section>

                <details class="card overflow-hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-6 marker:hidden">
                        <span>
                            <span class="block text-lg font-semibold text-slate-950">Tambah aksesoris</span>
                            <span class="mt-1 block text-sm text-slate-600">Opsional. Tambahkan controller, headset, atau game kalau dibutuhkan.</span>
                        </span>
                        <span class="inline-flex shrink-0 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-plus" aria-hidden="true"></i>
                            Pilih
                        </span>
                    </summary>

                    <div class="border-t border-slate-200 p-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            @forelse($accessories as $accessory)
                                <div class="rounded-lg border border-slate-200 bg-white p-4">
                                    <div class="flex gap-4">
                                        <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-100">
                                            @if($accessory->image)
                                    <img src="{{ $accessory->image_url }}" alt="{{ $accessory->name }}" class="h-full w-full object-cover">
                                            @else
                                                <i class="fas fa-headphones text-slate-400" aria-hidden="true"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-slate-950">{{ $accessory->name }}</h3>
                                            <p class="mt-1 text-sm text-slate-500">Rp {{ number_format($accessory->price, 0, ',', '.') }} / item</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ $accessory->stock }}</p>
                                        </div>
                                        <div class="w-20">
                                            <label class="sr-only" for="accessory-{{ $accessory->id }}">Jumlah {{ $accessory->name }}</label>
                                            <input id="accessory-{{ $accessory->id }}" type="number" name="accessories[{{ $accessory->id }}]" value="{{ old('accessories.' . $accessory->id, 0) }}" min="0" max="{{ $accessory->stock }}" class="w-full rounded-lg border-slate-300 text-center text-sm focus:border-sky-500 focus:ring-sky-500">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">Belum ada aksesoris tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </details>

                <section class="card p-6">
                    <label class="block text-sm font-semibold text-slate-700">Catatan</label>
                    <textarea name="notes" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" rows="3">{{ old('notes') }}</textarea>
                    @error('notes')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                </section>
            </div>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <h2 class="text-lg font-semibold text-slate-950">Ringkasan</h2>
                <dl class="mt-5 space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Per jam</dt>
                        <dd class="font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_hour, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Per hari</dt>
                        <dd class="font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_day, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-slate-500">Ongkir</dt>
                        <dd class="font-semibold text-slate-950">Sesuai kota</dd>
                    </div>
                </dl>

                <label class="mt-6 flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm text-slate-700">
                    <input type="checkbox" name="agree_terms" value="1" class="mt-1 rounded border-slate-300 text-slate-950 focus:ring-sky-500" required>
                    <span>Saya setuju dengan aturan rental dan biaya keterlambatan.</span>
                </label>
                @error('agree_terms')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror

                <button type="submit" class="btn btn-primary mt-5 w-full">
                    <i class="fas fa-credit-card mr-2" aria-hidden="true"></i>
                    Lanjut pembayaran
                </button>
            </aside>
        </form>
    </div>
</div>
@endsection
