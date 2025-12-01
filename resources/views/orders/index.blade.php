@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10 h-fit">
            <x-user-sidebar />
        </aside>
        <div>
            <h1 class="text-3xl font-bold mb-6">My Orders</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Order ID</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Price</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">#{{ $order->order_number }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 py-1 font-semibold leading-tight text-{{ $order->status === 'paid' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'red') }}-900 bg-{{ $order->status === 'paid' ? 'green' : ($order->status === 'pending' ? 'yellow' : 'red') }}-200 rounded-full">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-gray-500">You have no orders.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
        </div>
    
    <div class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Keluhan Saya</h2>
        <div class="card overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Order</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dibuat</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues ?? collect() as $issue)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">#{{ optional($issue->order)->order_number }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($issue->type) }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($issue->status) }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $issue->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                @if($issue->order)
                                    <a href="{{ route('orders.show', $issue->order) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Order</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">Belum ada keluhan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>
</div>
@endsection