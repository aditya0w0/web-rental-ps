@extends('layouts.app')

@section('title', 'Orders — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">All Orders</h1>

    <div class="card overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Order #</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pickup</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b">
                        <td class="px-5 py-4 text-sm">{{ $order->order_number ?? $order->id }}</td>
                        <td class="px-5 py-4 text-sm">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm">{{ $order->user?->name }}<div class="text-gray-500 text-xs">{{ $order->user?->email }}</div></td>
                        <td class="px-5 py-4 text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-sm">{{ $order->pickup_method === 'delivery' ? 'Delivery' : 'Store' }}</td>
                        <td class="px-5 py-4 text-sm">{{ ucfirst($order->status) }}</td>
                        <td class="px-5 py-4 text-sm text-right">
                            <div class="inline-flex items-center" style="gap:8px">
                                <a href="{{ route('admin.orders.show', $order) }}" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded">Lihat</a>
                                <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2 py-1 bg-green-50 text-green-700 rounded">Confirm</button>
                                </form>
                                <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reason" value="Rejected from list">
                                    <button type="submit" class="px-2 py-1 bg-red-50 text-red-700 rounded">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">Belum ada order.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
