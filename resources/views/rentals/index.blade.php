@extends('layouts.app')

@section('title', 'My Rentals')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Customer</p>
            <h1 class="section-title">My Rentals</h1>
            <p class="section-copy">Pantau rental PlayStation, pembayaran, dan proses pengambilan dari satu tempat.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[260px_1fr]">
            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                <x-user-sidebar />
            </aside>

            <section class="table-shell">
                <header class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-semibold text-slate-950">Rental terbaru</h2>
                    <a href="{{ route('products.playstation') }}" class="btn btn-secondary py-2 text-sm">Sewa lagi</a>
                </header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Tipe</th>
                                <th>Periode</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Fulfillment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentals as $rental)
                                @php
                                    $status = strtolower($rental->status ?? 'pending');
                                    $badge = in_array($status, ['completed', 'confirmed', 'active']) ? 'badge-success' : ($status === 'pending' ? 'badge-warning' : 'badge-danger');
                                @endphp
                                <tr>
                                    <td><a href="{{ route('user.rentals.show', $rental) }}" class="link-action">{{ $rental->type?->name }}</a></td>
                                    <td>{{ $rental->start_time?->format('d M Y H:i') }} - {{ $rental->end_time?->format('d M Y H:i') }}</td>
                                    <td>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                                    <td><span class="badge {{ $badge }}">{{ ucfirst($rental->status ?? 'pending') }}</span></td>
                                    <td>{{ ucfirst($rental->fulfillment_status ?? 'none') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-slate-500">Belum ada rental.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-200 px-5 py-4">{{ $rentals->links() }}</div>
            </section>
        </div>
    </div>
</div>
@endsection
