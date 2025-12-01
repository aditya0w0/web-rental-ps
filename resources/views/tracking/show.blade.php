@extends('layouts.app')

@section('title', 'Track Order')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Track Order: {{ $order->order_number }}</h1>

    <div class="card p-6">
        <div class="mb-4">
            <p class="text-lg font-semibold">Status: <span class="text-emerald-600">{{ ucfirst($order->status) }}</span></p>
        </div>
        <div class="mb-4">
            <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
        </div>
        <div class="mb-4">
            <p><strong>Total Amount:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Order History</h2>
            <div class="border-l-2 border-emerald-600 pl-4">
                <div class="mb-4">
                    <p class="font-semibold">Order Placed</p>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($order->payment_date)
                    <div class="mb-4">
                        <p class="font-semibold">Payment Confirmed</p>
                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->payment_date)->format('d M Y, H:i') }}</p>
                    </div>
                @endif
                @if($order->status === 'processing')
                    <div class="mb-4">
                        <p class="font-semibold">Processing</p>
                        <p class="text-sm text-gray-500">Your order is being processed.</p>
                    </div>
                @elseif($order->status === 'shipped')
                    <div class="mb-4">
                        <p class="font-semibold">Shipped</p>
                        <p class="text-sm text-gray-500">Your order has been shipped.</p>
                    </div>
                @elseif($order->status === 'completed')
                    <div class="mb-4">
                        <p class="font-semibold">Completed</p>
                        <p class="text-sm text-gray-500">Your order is complete.</p>
                    </div>
                @elseif($order->status === 'cancelled')
                    <div class="mb-4">
                        <p class="font-semibold">Cancelled</p>
                        <p class="text-sm text-gray-500">Your order has been cancelled.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection