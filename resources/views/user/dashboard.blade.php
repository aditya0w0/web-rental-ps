@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
            <div class="flex items-center justify-between">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Halo, {{ auth()->user()->name }}</h1>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Logout</button>
                </form>
            </div>
            <p class="text-gray-600">Ringkasan aktivitas Anda.</p>
            <p class="text-gray-500">Lanjutkan belanja aksesoris, cek pesanan, dan pantau status rental dengan cepat.</p>
        </div>

    <!-- Layout: Sidebar + Main -->
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <!-- Sidebar -->
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10">
            <nav class="space-y-2 text-sm">
                <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white/10 text-white/90">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16v12H4z" stroke-width="2"/><path d="M4 10h16" stroke-width="2"/></svg>
                    My Orders
                </a>
                <a href="{{ route('user.rentals.index') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white/10 text-white/90">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="6" y="8" width="12" height="8" rx="4" stroke-width="2"/><circle cx="10" cy="12" r="1"/><circle cx="14" cy="12" r="1"/></svg>
                    My Rentals
                </a>
                <a href="{{ url('/track') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white/10 text-white/90">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M3 12h18" stroke-width="2"/><path d="M12 3v18" stroke-width="2"/></svg>
                    Track Order
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-white/10 text-white/90">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="7" r="3"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                    Profile
                </a>
            </nav>
        </aside>

        <!-- Main -->
        <div>
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-gray-500 text-sm">Total Orders</div>
            <div class="text-2xl font-bold">{{ $stats['orders_total'] ?? 0 }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-gray-500 text-sm">Orders Completed</div>
            <div class="text-2xl font-bold">{{ $stats['orders_completed'] ?? 0 }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-gray-500 text-sm">Total Rentals</div>
            <div class="text-2xl font-bold">{{ $stats['rentals_total'] ?? 0 }}</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-gray-500 text-sm">Active Rentals</div>
            <div class="text-2xl font-bold">{{ $stats['rentals_active'] ?? 0 }}</div>
        </div>
    </div>

            <!-- Quick Actions (Browse only) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('products.playstation') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <h4 class="font-bold text-emerald-700">Browse PlayStation</h4>
            <p class="text-sm text-gray-600">Lihat tipe PS yang tersedia</p>
        </a>
        <a href="{{ route('products.accessories') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
            <h4 class="font-bold text-emerald-700">Browse Accessories</h4>
            <p class="text-sm text-gray-600">Lihat aksesoris yang tersedia</p>
        </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Orders Terbaru</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Order #</th>
                            <th class="py-2">Tanggal</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $o)
                            <tr class="border-t">
                                <td class="py-2"><a href="{{ route('orders.show', $o) }}" class="link">{{ $o->order_number ?? $o->id }}</a></td>
                                <td class="py-2">{{ $o->created_at?->format('d M Y H:i') }}</td>
                                <td class="py-2">
                                    @php $os = strtolower($o->status ?? 'pending'); $badge = in_array($os,['completed','delivered','shipped']) ? 'badge-success' : (in_array($os,['pending','processing']) ? 'badge-warning' : 'badge-danger'); @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($o->status ?? 'pending') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">Belum ada order</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Rentals -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 border-b">
                <h3 class="text-lg font-bold">Rental Terbaru</h3>
            </div>
            <div class="p-6">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Mulai</th>
                            <th class="py-2">Selesai</th>
                            <th class="py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRentals as $r)
                            <tr class="border-t">
                                <td class="py-2">{{ $r->start_time?->format('d M Y H:i') }}</td>
                                <td class="py-2">{{ $r->end_time?->format('d M Y H:i') }}</td>
                                <td class="py-2">
                                    @php $s = strtolower($r->status ?? ''); $badge = in_array($s,['completed','paid','delivered']) ? 'badge-success' : (in_array($s,['pending','processing','active']) ? 'badge-warning' : 'badge-danger'); @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($r->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-gray-500">Belum ada rental</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
            </div>
        </div>
    </div>
</div>
@endsection