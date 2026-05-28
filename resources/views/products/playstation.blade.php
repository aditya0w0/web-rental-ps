@extends('layouts.app')

@section('title', 'Rental PlayStation - PlayHub')

@section('content')
@php
    $currentUser = auth()->user();
    $isAdmin = $currentUser && method_exists($currentUser, 'isAdmin') ? $currentUser->isAdmin() : (($currentUser->role ?? null) === 'admin');
@endphp
<div class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <p class="section-eyebrow">Konsol</p>
            <h1 class="section-title">{{ $isAdmin ? 'Inventori PlayStation' : 'Rental PlayStation' }}</h1>
            <p class="section-copy">{{ $isAdmin ? 'Pantau tipe konsol, stok unit, dan harga dari sisi operasional.' : 'Bandingkan harga per jam atau per hari, lalu pilih jadwal rental.' }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($playstationTypes as $type)
                <article class="product-card">
                    <div class="product-image">
                        @if($type->image)
                            <img src="{{ asset('storage/' . $type->image) }}" alt="{{ $type->name }}">
                        @else
                            <i class="fas fa-gamepad text-5xl text-slate-400" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <h2 class="text-xl font-semibold text-slate-950">{{ $type->name }}</h2>
                            <span class="status-pill">{{ $type->available_units }} unit</span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-600 flex-1">{{ $type->description }}</p>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-slate-500">Per jam</p>
                                <p class="text-lg font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_hour, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Per hari</p>
                                <p class="text-lg font-semibold text-slate-950">Rp {{ number_format($type->rental_price_per_day, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($isAdmin)
                            <a href="{{ route('admin.playstation-types.edit', $type) }}" class="btn btn-secondary mt-6 w-full">Edit tipe konsol</a>
                        @else
                            <a href="{{ route('rent.create', $type) }}" class="btn btn-primary mt-6 w-full">Rental sekarang</a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">Belum ada PlayStation yang tersedia.</div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $playstationTypes->links() }}
        </div>
    </div>
</div>
@endsection
