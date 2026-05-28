@extends('layouts.app')

@section('title', 'Accessory Orders')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Customer</p>
            <h1 class="section-title">Accessory Orders</h1>
            <p class="section-copy">Pesanan di halaman ini hanya untuk pembelian aksesoris. Rental PlayStation ada di menu Rentals.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                <x-user-sidebar />
            </aside>

            <div class="space-y-6">
                @if(session('success'))
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
                @endif

                <section class="table-shell">
                    <header class="flex items-center justify-between gap-3">
                        <h2 class="text-lg font-semibold text-slate-950">Orders</h2>
                        <a href="{{ route('products.accessories') }}" class="btn btn-secondary py-2 text-sm">Belanja aksesoris</a>
                    </header>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $status = strtolower($order->status ?? 'pending');
                                        $badge = in_array($status, ['paid', 'completed']) ? 'badge-success' : ($status === 'pending' ? 'badge-warning' : 'badge-danger');
                                    @endphp
                                    <tr>
                                        <td class="font-semibold text-slate-950">#{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td><span class="badge {{ $badge }}">{{ ucfirst($order->status ?? 'pending') }}</span></td>
                                        <td class="text-right"><a href="{{ route('orders.show', $order) }}" class="link-action">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-slate-500">Belum ada accessory order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-slate-200 px-5 py-4">{{ $orders->links() }}</div>
                </section>

                <section class="table-shell">
                    <header><h2 class="text-lg font-semibold text-slate-950">Keluhan Saya</h2></header>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues ?? collect() as $issue)
                                    <tr>
                                        <td>#{{ optional($issue->order)->order_number }}</td>
                                        <td>{{ ucfirst($issue->type) }}</td>
                                        <td><span class="badge badge-warning">{{ ucfirst($issue->status) }}</span></td>
                                        <td>{{ $issue->created_at?->format('d M Y H:i') }}</td>
                                        <td class="text-right">
                                            @if($issue->order)
                                                <a href="{{ route('orders.show', $issue->order) }}" class="link-action">Lihat</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-slate-500">Belum ada keluhan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
