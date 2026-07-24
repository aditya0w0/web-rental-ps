@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $maxVolume = max(1, (int) collect($rentalsSeries ?? [])->max(), (int) collect($ordersSeries ?? [])->max());
    $maxRevenue = max(1, (int) collect($revenueSeries ?? [])->max());
    $riskBadge = fn ($risk) => match ($risk) {
        'low' => 'badge-success',
        'review' => 'badge-warning',
        'flagged' => 'badge-danger',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-eyebrow">Admin</p>
                <h1 class="section-title">Dashboard operasional</h1>
                <p class="section-copy">Review pembayaran, pantau rental, dan kelola pekerjaan harian dari satu layar.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <span class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 shadow-sm">{{ now()->format('d M Y H:i') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="stat-card"><span>Total revenue</span><strong>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</strong></div>
            <div class="stat-card"><span>Need payment review</span><strong>{{ $stats['payment_review'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Pending orders</span><strong>{{ $stats['pending_orders'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Pending rentals</span><strong>{{ $stats['pending_rentals'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Available units</span><strong>{{ $stats['available_units'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Accessories</span><strong>{{ $stats['total_accessories'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Active sessions</span><strong>{{ $stats['users_logged_in'] ?? 0 }}</strong></div>
            <div class="stat-card"><span>Late fee estimate</span><strong>Rp {{ number_format($lateFeeTotal ?? 0, 0, ',', '.') }}</strong></div>
        </div>

        <div class="mb-8 grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
            <a href="{{ route('admin.orders.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-receipt" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Orders</h2><p class="mt-1 text-sm text-slate-600">Accessory payments</p></a>
            <a href="{{ route('admin.rentals.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-clock" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Rentals</h2><p class="mt-1 text-sm text-slate-600">Console bookings</p></a>
            <a href="{{ route('admin.playstation-types.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-gamepad" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Inventory</h2><p class="mt-1 text-sm text-slate-600">Types and units</p></a>
            <a href="{{ route('admin.accessories.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-headphones" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Accessories</h2><p class="mt-1 text-sm text-slate-600">Stock and price</p></a>
            <a href="{{ route('admin.order-issues.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-triangle-exclamation" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Tickets</h2><p class="mt-1 text-sm text-slate-600">Complaints SLA</p></a>
            <a href="{{ route('admin.articles.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-newspaper" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Articles</h2><p class="mt-1 text-sm text-slate-600">Publish content</p></a>
            @if(auth()->user()?->isOwner())
                <a href="{{ route('admin.admin-users.index') }}" class="action-card"><span class="icon-box"><i class="fas fa-user-gear" aria-hidden="true"></i></span><h2 class="mt-4 text-base font-semibold text-slate-950">Employees</h2><p class="mt-1 text-sm text-slate-600">Admin access</p></a>
            @endif
        </div>

        <div class="mb-8 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="table-shell">
                <header class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Payment review queue</h2>
                        <p class="mt-1 text-sm text-slate-500">Order/rental yang sudah upload bukti dan butuh approval admin.</p>
                    </div>
                    <span class="badge badge-warning">{{ $paymentQueue->count() }} waiting</span>
                </header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Type</th><th>Customer</th><th>Total</th><th>Detector</th><th class="text-right">Action</th></tr></thead>
                        <tbody>
                            @forelse($paymentQueue as $item)
                                <tr>
                                    <td>
                                        <div class="font-semibold text-slate-950">{{ $item['type'] }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $item['code'] }}</div>
                                    </td>
                                    <td>{{ $item['customer'] }}</td>
                                    <td>Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $riskBadge($item['risk']) }}">{{ ucfirst($item['risk']) }}</span>
                                        <div class="mt-1 text-xs text-slate-500">{{ $item['provider'] }} · {{ $item['confidence'] }}%</div>
                                    </td>
                                    <td class="text-right"><a href="{{ $item['url'] }}" class="btn btn-secondary px-3 py-1.5 text-xs">Review</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-slate-500">No uploaded payment proof waiting.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="card p-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Payment status split</h2>
                        <p class="mt-1 text-sm text-slate-500">Pending bukan selalu proof baru. Angka dipisah supaya jelas.</p>
                    </div>
                    <i class="fas fa-chart-pie text-sky-700" aria-hidden="true"></i>
                </div>
                <div class="mt-6 space-y-4">
                    @foreach([
                        ['label' => 'Accessory orders pending', 'value' => $stats['pending_orders'] ?? 0, 'color' => 'bg-sky-600'],
                        ['label' => 'Rentals pending', 'value' => $stats['pending_rentals'] ?? 0, 'color' => 'bg-emerald-600'],
                        ['label' => 'Legacy transactions pending', 'value' => $stats['pending_legacy_transactions'] ?? 0, 'color' => 'bg-slate-500'],
                    ] as $row)
                        @php $barMax = max(1, $stats['pending_transactions'] ?? 1); @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-semibold text-slate-700">{{ $row['label'] }}</span>
                                <span class="text-slate-500">{{ $row['value'] }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $row['color'] }}" style="width: {{ min(100, (($row['value'] / $barMax) * 100)) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="mb-8 grid gap-6 xl:grid-cols-2">
            <section class="card p-6">
                <h2 class="text-lg font-semibold text-slate-950">Monthly activity</h2>
                <div class="mt-6 flex h-56 items-end gap-2 border-b border-slate-200">
                    @foreach($labels as $index => $label)
                        @php
                            $rentalHeight = max(3, (($rentalsSeries[$index] ?? 0) / $maxVolume) * 100);
                            $orderHeight = max(3, (($ordersSeries[$index] ?? 0) / $maxVolume) * 100);
                        @endphp
                        <div class="flex min-w-0 flex-1 items-end justify-center gap-1">
                            <div title="Rentals {{ $label }}" class="w-full max-w-3 rounded-t bg-emerald-500" style="height: {{ $rentalHeight }}%"></div>
                            <div title="Orders {{ $label }}" class="w-full max-w-3 rounded-t bg-sky-600" style="height: {{ $orderHeight }}%"></div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 flex items-center gap-5 text-xs font-semibold text-slate-500">
                    <span><span class="mr-2 inline-block h-2 w-2 rounded-full bg-emerald-500"></span>Rentals</span>
                    <span><span class="mr-2 inline-block h-2 w-2 rounded-full bg-sky-600"></span>Accessory orders</span>
                </div>
            </section>

            <section class="card p-6">
                <h2 class="text-lg font-semibold text-slate-950">Revenue trend</h2>
                <div class="mt-6 flex h-56 items-end gap-2 border-b border-slate-200">
                    @foreach($labels as $index => $label)
                        @php $height = max(3, (($revenueSeries[$index] ?? 0) / $maxRevenue) * 100); @endphp
                        <div class="flex min-w-0 flex-1 items-end justify-center">
                            <div title="Revenue {{ $label }}" class="w-full max-w-4 rounded-t bg-slate-900" style="height: {{ $height }}%"></div>
                        </div>
                    @endforeach
                </div>
                <p class="mt-3 text-xs font-semibold text-slate-500">Paid legacy transaction revenue by month.</p>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="table-shell">
                <header><h2 class="text-lg font-semibold text-slate-950">Recent accessory orders</h2></header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $order) }}" class="link">{{ $order->order_number ?? $order->id }}</a></td>
                                    <td>{{ $order->user?->name ?? '-' }}</td>
                                    <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                    <td><span class="badge {{ in_array($order->status, ['paid', 'completed'], true) ? 'badge-success' : ($order->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">{{ ucfirst($order->status ?? 'pending') }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-slate-500">Belum ada order.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="table-shell">
                <header><h2 class="text-lg font-semibold text-slate-950">Recent rentals</h2></header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead><tr><th>Customer</th><th>Console</th><th>Start</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentRentals as $rental)
                                <tr>
                                    <td>{{ $rental->user?->name ?? '-' }}</td>
                                    <td>{{ $rental->type?->name ?? '-' }}</td>
                                    <td>{{ $rental->start_time?->format('d M Y H:i') }}</td>
                                    <td><span class="badge {{ in_array($rental->status, ['completed', 'active', 'confirmed'], true) ? 'badge-success' : ($rental->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">{{ ucfirst($rental->status ?? 'pending') }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-slate-500">Belum ada rental.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
