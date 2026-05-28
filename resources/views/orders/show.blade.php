@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order Details</h1>
        <a href="{{ route('orders.index') }}" class="link">&larr; Back to My Orders</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Order Information</h2>
                <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> 
                    @php $s=$order->status; $map=['paid'=>['bg'=>'bg-green-100','tx'=>'text-green-700'],'pending'=>['bg'=>'bg-yellow-100','tx'=>'text-yellow-800'],'failed'=>['bg'=>'bg-red-100','tx'=>'text-red-700']]; @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $map[$s]['bg'] ?? 'bg-gray-100' }} {{ $map[$s]['tx'] ?? 'text-gray-700' }}">{{ ucfirst($order->status) }}</span>
                </p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Customer</h2>
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Pickup/Delivery</h2>
                <p><strong>Method:</strong> {{ $order->pickup_method === 'delivery' ? 'Delivery' : 'Store Pickup' }}</p>
                @if($order->pickup_method === 'delivery')
                    <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    @if($order->tracking_number)
                        <p><strong>Tracking #:</strong> {{ $order->tracking_number }}</p>
                    @endif
                @endif
            </div>
            @if($order->status === 'pending')
                <div>
                    <a href="{{ route('orders.payment', $order) }}" class="btn btn-primary">Lanjut pembayaran</a>
                </div>
            @elseif($order->payment_proof)
                <div>
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Payment Proof</h2>
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="w-32 h-32 object-cover rounded-md shadow-md">
                    </a>
                </div>
            @endif
        </div>
        @php
            $deadline = $order->delivered_at?->copy()->addHours(24);
            $canComplain = (($order->fulfillment_status ?? 'none') === 'completed') && $order->delivered_at && now()->lte($deadline);
            $remain = ($deadline) ? \Carbon\Carbon::now()->diffForHumans($deadline, ['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) : null;
            $complaintReason = null;
            if (!$canComplain) {
                $complaintReason = (($order->fulfillment_status ?? 'none') === 'completed' && $order->delivered_at)
                    ? 'Batas pengajuan 24 jam setelah barang diterima sudah lewat.'
                    : 'Keluhan aktif setelah order completed dan barang diterima.';
            }
        @endphp
        <div class="mt-6">
            <div class="rounded-lg border {{ $canComplain ? 'border-rose-200 bg-rose-50' : 'border-slate-200 bg-slate-50' }}">
                <div class="p-4 md:p-5 flex items-center justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white {{ $canComplain ? 'text-rose-700' : 'text-slate-600' }}">
                            <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        </span>
                        <div>
                            <div class="{{ $canComplain ? 'text-rose-900' : 'text-slate-950' }} font-semibold">Complaint & SLA</div>
                            @if($deadline)
                                <div class="text-xs {{ $canComplain ? 'text-rose-800' : 'text-slate-600' }}">Window komplain: {{ $deadline->format('d M Y H:i') }} @if($remain && $canComplain) (sisa {{ $remain }}) @endif</div>
                            @endif
                            @if($complaintReason)
                                <div class="mt-1 text-sm text-slate-600">{{ $complaintReason }}</div>
                            @else
                                <div class="mt-1 text-sm text-rose-800">Admin wajib respon dalam {{ config('service.complaint_sla.response_hours', 4) }} jam dan target selesai {{ config('service.complaint_sla.resolution_hours', 48) }} jam.</div>
                            @endif
                        </div>
                    </div>
                    @if($canComplain)
                        <a href="{{ route('orders.issue.create', $order) }}" class="btn btn-secondary">Laporkan keluhan</a>
                    @else
                        <span class="inline-flex cursor-not-allowed items-center rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-400">Belum bisa komplain</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Fulfillment Timeline</h2>
        <div class="border-l-2 border-sky-600 pl-4">
            <div class="mb-3">
                <div class="font-semibold">Order Placed</div>
                <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</div>
            </div>
            @if($order->payment_proof)
            <div class="mb-3">
                <div class="font-semibold">Payment Proof Uploaded</div>
            </div>
            @endif
            @if($order->status === 'paid' && $order->payment_date)
            <div class="mb-3">
                <div class="font-semibold">Payment Confirmed</div>
                <div class="text-sm text-gray-500">{{ $order->payment_date->format('d M Y, H:i') }}</div>
            </div>
            @elseif($order->status === 'failed')
            <div class="mb-3">
                <div class="font-semibold">Payment Rejected</div>
                <div class="text-sm text-gray-500">Reason: {{ $order->rejection_reason }}</div>
            </div>
            @endif
            <div class="mb-3">
                @php $fs = $order->fulfillment_status ?? 'none'; @endphp
                <div class="font-semibold">Fulfillment: 
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                        {{ $fs==='completed' ? 'bg-emerald-100 text-emerald-700' : ($fs==='processing' ? 'bg-slate-100 text-slate-700' : ($fs==='ready_for_pickup' ? 'bg-blue-100 text-blue-700' : ($fs==='shipped' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-700'))) }}">
                        {{ ucfirst($fs) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold mb-4">Items Ordered</h2>
    <div class="space-y-4">
        @foreach($order->items as $item)
        <div class="bg-white shadow-md rounded-lg p-4">
            <div class="flex items-start gap-6">
                <div class="w-40 h-40 flex-shrink-0">
                    <img class="w-full h-full object-cover rounded-lg" src="{{ $item->accessory->image ? asset('storage/' . $item->accessory->image) : 'https://via.placeholder.com/300x300' }}" alt="{{ $item->accessory->name }}"/>
                </div>
                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Product</div>
                        <div class="font-semibold">{{ $item->accessory->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Price</div>
                        <div>Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Quantity</div>
                        <div>{{ $item->quantity }}</div>
                    </div>
                    <div class="sm:col-span-3">
                        <div class="text-sm text-gray-500">Subtotal</div>
                        <div class="text-lg font-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Keluhan Order</h2>
        @php $issues = $order->issues ?? collect(); @endphp
        @if($issues->count())
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600">
                        <th class="py-2">Tipe</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">SLA</th>
                        <th class="py-2">Respon Admin</th>
                        <th class="py-2">Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $i)
                        <tr class="border-t">
                            <td class="py-2">{{ ucfirst($i->type) }}</td>
                            <td class="py-2">{{ ucfirst($i->status) }}</td>
                            <td class="py-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $i->sla_status === 'breached' ? 'bg-rose-100 text-rose-700' : ($i->sla_status === 'at_risk' ? 'bg-amber-100 text-amber-800' : ($i->sla_status === 'met' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700')) }}">
                                    {{ str_replace('_', ' ', ucfirst($i->sla_status ?? 'on_track')) }}
                                </span>
                            </td>
                            <td class="py-2">{{ $i->admin_response ? Str::limit($i->admin_response, 80) : '-' }}</td>
                            <td class="py-2">{{ $i->created_at?->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-sm text-gray-600">Belum ada keluhan untuk order ini.</div>
        @endif
    </div>
</div>
@endsection
