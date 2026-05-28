@extends('layouts.app')

@section('title', 'Aksesoris - PlayHub')

@section('content')
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && method_exists($currentUser, 'isAdmin') ? $currentUser->isAdmin() : (($currentUser->role ?? null) === 'admin');
@endphp
<div class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <p class="section-eyebrow">{{ $isAdmin ? 'Inventori' : 'Toko' }}</p>
            <h1 class="section-title">{{ $isAdmin ? 'Kelola Aksesoris' : 'Aksesoris PlayStation' }}</h1>
            <p class="section-copy">{{ $isAdmin ? 'Lihat stok dan masuk ke halaman pengelolaan item.' : 'Pilih aksesoris yang tersedia dan tambahkan langsung ke keranjang.' }}</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($accessories as $accessory)
                <article class="product-card">
                    <div class="product-image">
                        @if($accessory->image)
                            <img src="{{ asset('storage/' . $accessory->image) }}" alt="{{ $accessory->name }}">
                        @else
                            <i class="fas fa-headphones text-5xl text-slate-400" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-base font-semibold text-slate-950">{{ $accessory->name }}</h2>
                            <span class="status-pill">{{ $accessory->stock }}</span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">{{ $accessory->brand ?? 'PlayHub' }}</p>

                        <div class="mt-5 flex items-center justify-between gap-3">
                            <p class="text-xl font-semibold text-slate-950">Rp {{ number_format($accessory->price, 0, ',', '.') }}</p>
                            @if($accessory->category)
                                <p class="text-sm text-slate-500">{{ $accessory->category }}</p>
                            @endif
                        </div>

                        @if($isAdmin)
                            <a href="{{ route('admin.accessories.edit', $accessory) }}" class="btn btn-secondary mt-5 w-full">
                                <i class="fas fa-pen mr-2" aria-hidden="true"></i>
                                Edit aksesoris
                            </a>
                        @else
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-5">
                                @csrf
                                <input type="hidden" name="accessory_id" value="{{ $accessory->id }}">
                                <button type="submit" class="btn btn-primary w-full">
                                    <i class="fas fa-cart-plus mr-2" aria-hidden="true"></i>
                                    Tambah ke keranjang
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">Belum ada aksesoris yang tersedia.</div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $accessories->links() }}
        </div>
    </div>
</div>
@endsection
