@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
<div class="bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Dashboard</p>
                <h1 class="section-title">Halo, {{ auth()->user()->name }}</h1>
                <p class="section-copy">Pantau accessory orders, rental PlayStation, dan lanjutkan aktivitas PlayHub dari satu tempat.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="stat-card"><span>Accessory Orders</span><strong>{{ $stats['orders_total'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Orders Completed</span><strong>{{ $stats['orders_completed'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Total Rentals</span><strong>{{ $stats['rentals_total'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Active Rentals</span><strong>{{ $stats['rentals_active'] ?? 0 }}</strong></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6">
            <aside class="rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                <x-user-sidebar />
            </aside>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <a href="{{ route('products.playstation') }}" class="action-card">
                        <span class="icon-box"><i class="fas fa-gamepad" aria-hidden="true"></i></span>
                        <h2 class="mt-4 text-lg font-semibold text-slate-950">Browse PlayStation</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Lihat tipe PS yang tersedia.</p>
                    </a>
                    <a href="{{ route('products.accessories') }}" class="action-card">
                        <span class="icon-box"><i class="fas fa-bag-shopping" aria-hidden="true"></i></span>
                        <h2 class="mt-4 text-lg font-semibold text-slate-950">Browse Accessories</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Beli aksesoris terpisah atau tambahkan langsung saat checkout rental.</p>
                    </a>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <section class="table-shell">
                        <header><h2 class="text-lg font-semibold text-slate-950">Accessory Orders Terbaru</h2></header>
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead><tr><th>Order #</th><th>Tanggal</th><th>Status</th></tr></thead>
                                <tbody>
                                    @forelse($recentOrders as $order)
                                        <tr>
                                            <td><a href="{{ route('orders.show', $order) }}" class="link">{{ $order->order_number ?? $order->id }}</a></td>
                                            <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                            <td>
                                                @php $status = strtolower($order->status ?? 'pending'); $badge = in_array($status, ['completed','delivered','shipped']) ? 'badge-success' : (in_array($status, ['pending','processing']) ? 'badge-warning' : 'badge-danger'); @endphp
                                                <span class="badge {{ $badge }}">{{ ucfirst($order->status ?? 'pending') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-slate-500">Belum ada order.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <section class="table-shell">
                        <header><h2 class="text-lg font-semibold text-slate-950">Rental Terbaru</h2></header>
                        <div class="overflow-x-auto">
                            <table class="data-table">
                                <thead><tr><th>Mulai</th><th>Selesai</th><th>Status</th></tr></thead>
                                <tbody>
                                    @forelse($recentRentals as $rental)
                                        <tr>
                                            <td>{{ $rental->start_time?->format('d M Y H:i') }}</td>
                                            <td>{{ $rental->end_time?->format('d M Y H:i') }}</td>
                                            <td>
                                                @php $status = strtolower($rental->status ?? ''); $badge = in_array($status, ['completed','paid','delivered']) ? 'badge-success' : (in_array($status, ['pending','processing','active']) ? 'badge-warning' : 'badge-danger'); @endphp
                                                <span class="badge {{ $badge }}">{{ ucfirst($rental->status ?? 'pending') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="text-center text-slate-500">Belum ada rental.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
