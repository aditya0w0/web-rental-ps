@props(['orientation' => 'vertical', 'theme' => 'dark'])
@php $horizontal = strtolower($orientation) === 'horizontal'; $dark = strtolower($theme) === 'dark'; $link = ($horizontal ? 'inline-flex' : 'flex').' items-center gap-2 px-3 py-2 rounded '.($dark ? 'hover:bg-white/10 text-white/90' : 'hover:bg-gray-100 text-gray-800'); @endphp
<nav class="{{ $horizontal ? 'flex items-center gap-3' : 'space-y-2 text-sm' }}">
    <a href="{{ route('orders.index') }}" class="{{ $link }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16v12H4z" stroke-width="2"/><path d="M4 10h16" stroke-width="2"/></svg>
        Orders
    </a>
    <a href="{{ route('user.rentals.index') }}" class="{{ $link }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="6" y="8" width="12" height="8" rx="4" stroke-width="2"/><circle cx="10" cy="12" r="1"/><circle cx="14" cy="12" r="1"/></svg>
        Rentals
    </a>
    <a href="{{ route('transaction.track') }}" class="{{ $link }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12h18" stroke-width="2"/><path d="M12 3v18" stroke-width="2"/></svg>
        Track Order
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ $link }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="7" r="3"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
        Profile
    </a>
    <a href="{{ route('orders.issues.index') }}" class="{{ $link }}">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Z" stroke-width="2"/><path d="M12 7v6" stroke-width="2"/><circle cx="12" cy="17" r="1"/></svg>
        Keluhan Saya
    </a>
</nav>