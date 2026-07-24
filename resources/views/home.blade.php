@extends('layouts.app')

@section('title', 'Beranda - PlayHub')

@section('content')
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && method_exists($currentUser, 'isAdmin') ? $currentUser->isAdmin() : (($currentUser->role ?? null) === 'admin');
    $heroImage = asset('images/hero/dhimas-hero-primary.png');
    $carouselImages = collect([
        asset('images/hero/dhimas-hero-primary.png'),
        asset('images/hero/dhimas-hero-logo.png'),
        asset('images/hero/dhimas-hero-services.png'),
    ]);
@endphp

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-[1.02fr_.98fr] gap-10 lg:gap-14 items-center">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-700">Rental PlayStation Pekalongan</p>
                <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-semibold tracking-normal text-slate-950 leading-tight">
                    Sewa PS5 dan aksesoris gaming tanpa ribet.
                </h1>
                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Pilih konsol, atur jadwal, tambah aksesoris, lalu lanjutkan pembayaran dalam alur yang jelas.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ $isAdmin ? route('admin.dashboard') : route('products.playstation') }}" class="btn btn-primary min-h-11 px-5">
                        <i class="{{ $isAdmin ? 'fas fa-chart-line' : 'fas fa-gamepad' }} mr-2" aria-hidden="true"></i>
                        {{ $isAdmin ? 'Buka dashboard' : 'Rental sekarang' }}
                    </a>
                    <a href="{{ $isAdmin ? route('admin.playstation-types.index') : route('products.accessories') }}" class="btn btn-secondary min-h-11 px-5">
                        <i class="{{ $isAdmin ? 'fas fa-boxes-stacked' : 'fas fa-bag-shopping' }} mr-2" aria-hidden="true"></i>
                        {{ $isAdmin ? 'Kelola inventori' : 'Lihat aksesoris' }}
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-3 gap-3 max-w-lg">
                    <div class="surface-panel p-4">
                        <p class="text-2xl font-semibold text-slate-950">24</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-500">Jam layanan</p>
                    </div>
                    <div class="surface-panel p-4">
                        <p class="text-2xl font-semibold text-slate-950">{{ $playstationTypes->count() }}</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-500">Tipe konsol</p>
                    </div>
                    <div class="surface-panel p-4">
                        <p class="text-2xl font-semibold text-slate-950">{{ $accessoriesCount ?? $accessories->count() }}</p>
                        <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-500">Aksesoris</p>
                    </div>
                </div>
            </div>

            <div
                class="relative"
                x-data="{
                    active: 0,
                    images: @js($carouselImages->values()),
                    timer: null,
                    start() {
                        this.timer = setInterval(() => {
                            this.active = (this.active + 1) % this.images.length;
                        }, 4200);
                    },
                    choose(index) {
                        this.active = index;
                        clearInterval(this.timer);
                        this.start();
                    }
                }"
                x-init="start()"
            >
                <div class="relative aspect-square overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <img
                        :src="images[active]"
                        alt="Dhimas Dhika PS rental PlayStation"
                        class="absolute inset-0 h-full w-full object-contain"
                    >
                </div>
                <div class="mt-4 grid grid-cols-3 gap-3">
                    @foreach(($carouselImages->take(3)->isNotEmpty() ? $carouselImages->take(3) : collect([$heroImage, $heroImage, $heroImage])) as $index => $image)
                        <button
                            type="button"
                            class="aspect-square overflow-hidden rounded-lg border bg-white p-0 transition hover:border-sky-300"
                            :class="active === {{ $index }} ? 'border-sky-500 ring-2 ring-sky-500 ring-offset-2' : 'border-slate-200'"
                            @click="choose({{ $index }})"
                            aria-label="Tampilkan hero {{ $index + 1 }}"
                        >
                            <img src="{{ $image }}" alt="Galeri PlayHub {{ $index + 1 }}" class="h-full w-full object-contain">
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section id="artikel" class="bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <p class="section-eyebrow">Konsol</p>
                <h2 class="section-title">Pilih PlayStation</h2>
                <p class="section-copy">Harga mudah dibandingkan, stok terlihat, dan tombol aksi selalu dekat.</p>
            </div>
            <a href="{{ $isAdmin ? route('admin.playstation-types.index') : route('products.playstation') }}" class="link-action">{{ $isAdmin ? 'Kelola konsol' : 'Lihat semua' }}</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($playstationTypes as $type)
                <article class="product-card">
                    <div class="product-image">
                        @if($type->image)
                            <img src="{{ $type->image_url }}" alt="{{ $type->name }}">
                        @else
                            <i class="fas fa-gamepad text-5xl text-slate-400" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="text-lg font-semibold text-slate-950">{{ $type->name }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600 flex-1">{{ Str::limit($type->description, 96) }}</p>
                        <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <dt class="text-slate-500">Per jam</dt>
                                <dd class="font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_hour, 0, ',', '.') }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Per hari</dt>
                                <dd class="font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_day, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                        <div class="mt-5 flex items-center justify-between gap-3">
                            <span class="status-pill">{{ $type->available_units }} unit</span>
                            @if($isAdmin)
                                <a href="{{ route('admin.playstation-types.edit', $type) }}" class="btn btn-secondary px-4 py-2 text-sm">Edit</a>
                            @else
                                <a href="{{ route('rent.create', $type) }}" class="btn btn-primary px-4 py-2 text-sm">Rental</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">Belum ada konsol yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <p class="section-eyebrow">Aksesoris</p>
                <h2 class="section-title">Lengkapi sesi gaming</h2>
                <p class="section-copy">Stok dan harga ditampilkan ringkas supaya pilihan lebih cepat.</p>
            </div>
            <a href="{{ $isAdmin ? route('admin.accessories.index') : route('products.accessories') }}" class="link-action">{{ $isAdmin ? 'Kelola aksesoris' : 'Lihat semua' }}</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($accessories as $accessory)
                <article class="product-card">
                    <div class="product-image">
                        @if($accessory->image)
                            <img src="{{ $accessory->image_url }}" alt="{{ $accessory->name }}">
                        @else
                            <i class="fas fa-headphones text-5xl text-slate-400" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-base font-semibold text-slate-950">{{ $accessory->name }}</h3>
                            <span class="status-pill">{{ $accessory->stock }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ $accessory->brand ?? 'PlayHub' }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-600 flex-1">{{ Str::limit($accessory->description, 84) }}</p>
                        <p class="mt-5 text-xl font-semibold text-slate-950">Rp {{ number_format($accessory->price, 0, ',', '.') }}</p>
                        @if($isAdmin)
                            <a href="{{ route('admin.accessories.edit', $accessory) }}" class="btn btn-secondary mt-4 w-full text-sm">
                                <i class="fas fa-pen mr-2" aria-hidden="true"></i>
                                Edit aksesoris
                            </a>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                                @csrf
                                <input type="hidden" name="accessory_id" value="{{ $accessory->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-full text-sm">
                                    <i class="fas fa-cart-plus mr-2" aria-hidden="true"></i>
                                    Keranjang
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">Belum ada aksesoris yang tersedia.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="mb-8">
            <p class="section-eyebrow">Artikel</p>
            <h2 class="section-title">Panduan singkat</h2>
            <p class="section-copy">Info perawatan dan rekomendasi game dari PlayHub.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse($articles ?? [] as $article)
                <article class="product-card">
                    @if(!empty($article->image))
                        @php
                            $articleImage = str_starts_with($article->image, 'http')
                                ? $article->image
                                : (str_starts_with($article->image, 'images/') ? asset($article->image) : asset('storage/' . $article->image));
                        @endphp
                        <div class="aspect-[16/9] overflow-hidden bg-slate-100">
                            <img src="{{ $articleImage }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                        </div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $article->author_name ?? 'PlayHub' }} / {{ optional($article->published_at)->format('d M Y') }}</p>
                        <h3 class="mt-3 text-lg font-semibold text-slate-950">{{ $article->title }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $article->excerpt ?? Str::limit(strip_tags($article->body), 120) }}</p>
                        <a href="{{ route('articles.show', $article->slug) }}" class="mt-4 inline-flex items-center text-sm font-semibold text-sky-700 hover:text-sky-900">
                            Baca selengkapnya
                            <i class="fas fa-arrow-right ml-2 text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @empty
                <div class="empty-state">Belum ada artikel.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="feature-block">
                <span class="icon-box"><i class="fas fa-receipt" aria-hidden="true"></i></span>
                <h3>Harga transparan</h3>
                <p>Biaya rental dan pembelian ditampilkan sebelum checkout.</p>
            </div>
            <div class="feature-block">
                <span class="icon-box"><i class="fas fa-truck-fast" aria-hidden="true"></i></span>
                <h3>Pengiriman lokal</h3>
                <p>Opsi antar dan ambil langsung tetap mudah ditemukan.</p>
            </div>
            <div class="feature-block">
                <span class="icon-box"><i class="fas fa-headset" aria-hidden="true"></i></span>
                <h3>Bantuan cepat</h3>
                <p>Status pesanan dan rental bisa dicek dari akun pengguna.</p>
            </div>
        </div>
    </div>
</section>
@endsection
