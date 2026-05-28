@props(['orientation' => 'vertical'])
@php
    $horizontal = strtolower($orientation) === 'horizontal';
    $link = ($horizontal ? 'inline-flex' : 'flex') . ' items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100';
@endphp
<nav class="{{ $horizontal ? 'flex flex-wrap items-center gap-2' : 'space-y-1' }}">
    <a href="{{ route('dashboard') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-gauge-high" aria-hidden="true"></i></span>
        Dashboard
    </a>
    <a href="{{ route('orders.index') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-receipt" aria-hidden="true"></i></span>
        Accessory Orders
    </a>
    <a href="{{ route('user.rentals.index') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
        Rentals
    </a>
    <a href="{{ route('transaction.track') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-location-dot" aria-hidden="true"></i></span>
        Track Order
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-user" aria-hidden="true"></i></span>
        Profile
    </a>
    <a href="{{ route('orders.issues.index') }}" class="{{ $link }}">
        <span class="icon-box h-8 w-8 text-sm"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i></span>
        Keluhan Saya
    </a>
</nav>
