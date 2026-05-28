@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="section-eyebrow">Admin</p>
                <h1 class="section-title">Reports</h1>
                <p class="section-copy">Export paid accessory orders and completed console rentals.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('reports.export.pdf', request()->query()) }}" class="btn btn-secondary">
                    <i class="fas fa-file-pdf mr-2" aria-hidden="true"></i>
                    PDF
                </a>
                <a href="{{ route('reports.export.excel', request()->query()) }}" class="btn btn-primary">
                    <i class="fas fa-file-excel mr-2" aria-hidden="true"></i>
                    Excel
                </a>
            </div>
        </div>

        <section class="card mb-8 p-6">
            <form action="{{ route('reports.index') }}" method="GET" class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
                <div>
                    <x-input-label for="start_date" value="Start date" />
                    <x-text-input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="mt-2 block w-full" />
                </div>
                <div>
                    <x-input-label for="end_date" value="End date" />
                    <x-text-input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="mt-2 block w-full" />
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </section>

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card"><span>Accessory orders</span><strong>{{ $sales->count() }}</strong></div>
            <div class="stat-card"><span>Accessory revenue</span><strong>Rp {{ number_format($salesTotal ?? 0, 0, ',', '.') }}</strong></div>
            <div class="stat-card"><span>Completed rentals</span><strong>{{ $rentals->count() }}</strong></div>
            <div class="stat-card"><span>Rental revenue</span><strong>Rp {{ number_format($rentalsTotal ?? 0, 0, ',', '.') }}</strong></div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <section class="table-shell">
                <header><h2 class="text-lg font-semibold text-slate-950">Accessory sales</h2></header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr><th>Order</th><th>Date</th><th>Customer</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td><a href="{{ route('admin.orders.show', $sale) }}" class="link">{{ $sale->order_number }}</a></td>
                                    <td>{{ $sale->created_at?->format('d M Y') }}</td>
                                    <td>{{ $sale->user?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-slate-500">No sales data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="table-shell">
                <header><h2 class="text-lg font-semibold text-slate-950">Console rentals</h2></header>
                <div class="overflow-x-auto">
                    <table class="data-table">
                        <thead>
                            <tr><th>Rental</th><th>Start</th><th>End</th><th>Customer</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($rentals as $rental)
                                <tr>
                                    <td><a href="{{ route('admin.rentals.show', $rental) }}" class="link">#{{ $rental->id }}</a></td>
                                    <td>{{ $rental->start_time?->format('d M Y') }}</td>
                                    <td>{{ $rental->end_time?->format('d M Y') }}</td>
                                    <td>{{ $rental->user?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-slate-500">No rental data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
