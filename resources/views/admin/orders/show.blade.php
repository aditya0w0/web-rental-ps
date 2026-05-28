@extends('layouts.app')

@section('title', 'Order #'.$order->order_number.' — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->order_number }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="link">&larr; Back</a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <div class="text-gray-500 text-sm">Customer</div>
                <div class="font-semibold">{{ $order->user?->name }}</div>
                <div class="text-sm text-gray-600">{{ $order->user?->email }}</div>
                @php $phone = optional($order->user)->phone; @endphp
                @if($phone)
                    <div class="mt-1 text-sm text-gray-600"><span class="font-semibold">Phone:</span> {{ $phone }}
                        <a target="_blank" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$phone) }}" class="ml-2 text-emerald-600">WA</a>
                    </div>
                @endif
            </div>
            <div>
                <div class="text-gray-500 text-sm">Total</div>
                <div class="text-2xl font-bold text-emerald-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                <div class="text-gray-500 text-sm mt-2">Status: {{ ucfirst($order->status) }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Pickup</div>
                <div class="font-semibold">{{ $order->pickup_method === 'delivery' ? 'Delivery' : 'Store Pickup' }}</div>
                @if($order->pickup_method === 'delivery')
                    <div class="text-sm text-gray-600 mt-1">{{ $order->delivery_address }}</div>
                    <div class="mt-1 text-sm text-gray-600"><span class="font-semibold">City:</span> {{ ucfirst($order->delivery_city) }}</div>
                    <div class="mt-1 text-sm text-gray-600"><span class="font-semibold">Shipping Cost:</span> Rp {{ number_format($order->shipping_cost,0,',','.') }}</div>
                    @if($order->tracking_number)
                        <div class="mt-2 text-sm"><span class="font-semibold">Tracking #:</span> {{ $order->tracking_number }}</div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Items</h2>
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b">
                        <td class="px-5 py-4">
                            <div class="w-20 h-20">
                                <img class="w-full h-full object-cover rounded-md" src="{{ $item->accessory?->image ? asset('storage/' . $item->accessory->image) : 'https://via.placeholder.com/150' }}" alt="{{ $item->accessory?->name }}"/>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm">{{ $item->accessory?->name }}</td>
                        <td class="px-5 py-4 text-sm">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-sm">{{ $item->quantity }}</td>
                        <td class="px-5 py-4 text-sm">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Payment</h2>
        @if($order->payment_proof)
            <div class="grid gap-5 md:grid-cols-[160px_1fr]">
                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="block overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof" class="h-40 w-full object-cover">
                </a>
                <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
                    @php
                        $proofRisk = $order->payment_proof_risk ?? 'not_checked';
                        $proofBadge = $proofRisk === 'low' ? 'badge-success' : (in_array($proofRisk, ['review', 'flagged'], true) ? ($proofRisk === 'flagged' ? 'badge-danger' : 'badge-warning') : 'bg-slate-100 text-slate-700');
                    @endphp
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="badge {{ $proofBadge }}">{{ ucfirst(str_replace('_', ' ', $proofRisk)) }}</span>
                        <span class="text-sm font-semibold text-slate-700">{{ $order->payment_proof_provider ?? 'Provider not detected' }}</span>
                        <span class="text-sm text-slate-500">{{ (int) ($order->payment_proof_confidence ?? 0) }}% confidence</span>
                    </div>
                    <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                        <div><dt class="font-semibold text-slate-500">File</dt><dd class="mt-1 text-slate-950">{{ $order->payment_proof_original_name ?? basename($order->payment_proof) }}</dd></div>
                        <div><dt class="font-semibold text-slate-500">Analyzed</dt><dd class="mt-1 text-slate-950">{{ $order->payment_proof_analyzed_at?->format('d M Y H:i') ?? '-' }}</dd></div>
                    </dl>
                    @if(!empty($order->payment_proof_flags))
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($order->payment_proof_flags as $flag)
                                <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-700">{{ str_replace('_', ' ', $flag) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="text-sm text-gray-600">No payment proof uploaded.</div>
        @endif
        <div class="flex gap-3 mt-4 items-center">
            <form method="POST" action="{{ route('admin.orders.confirm', $order) }}" class="flex flex-col gap-3">
                @csrf
                @method('PATCH')
                @if(in_array($order->payment_proof_risk, ['review', 'flagged'], true))
                    <label class="flex items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-900">
                        <input type="checkbox" name="review_acknowledged" value="1" class="mt-1 rounded border-amber-300 text-slate-950 focus:ring-sky-500">
                        <span>Saya sudah review manual bukti yang ditandai sistem.</span>
                    </label>
                @endif
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Confirm Payment</button>
            </form>
            <form method="POST" action="{{ route('admin.orders.reject', $order) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="text" name="reason" placeholder="Alasan reject" class="border rounded px-3 py-2" required>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Order Issues</h2>
        @php $issues = $order->issues ?? collect(); @endphp
        @if($issues->count())
            <table class="min-w-full leading-normal text-sm">
                <thead>
                    <tr>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Submitted</th>
                        <th class="px-5 py-3 bg-gray-100"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $i)
                        <tr class="border-b">
                            <td class="px-5 py-4 text-sm">{{ ucfirst($i->type) }}</td>
                            <td class="px-5 py-4 text-sm">{{ ucfirst($i->status) }}</td>
                            <td class="px-5 py-4 text-sm">{{ $i->created_at?->format('d M Y H:i') }}</td>
                            <td class="px-5 py-4 text-sm"><a href="{{ route('admin.order-issues.show', $i) }}" class="px-3 py-1 rounded bg-indigo-600 text-white">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-sm text-gray-600">Belum ada keluhan untuk order ini.</div>
        @endif
        <div class="mt-3">
            <a href="{{ route('admin.order-issues.index') }}" class="link">Lihat semua keluhan</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Fulfillment</h2>
        <div class="mb-2">Status: <span class="font-semibold">{{ ucfirst($order->fulfillment_status ?? 'none') }}</span></div>
        <form method="POST" action="{{ route('admin.orders.fulfillment', $order) }}" class="flex items-center" style="gap:8px">
            @csrf
            @method('PATCH')
            <select name="status" class="border rounded px-3 py-2">
                <option value="none" {{ ($order->fulfillment_status ?? 'none')==='none'?'selected':'' }}>None</option>
                <option value="processing" {{ $order->fulfillment_status==='processing'?'selected':'' }}>Processing</option>
                <option value="shipped" {{ $order->fulfillment_status==='shipped'?'selected':'' }}>Shipped</option>
                <option value="ready_for_pickup" {{ $order->fulfillment_status==='ready_for_pickup'?'selected':'' }}>Ready for Pickup</option>
                <option value="completed" {{ $order->fulfillment_status==='completed'?'selected':'' }}>Completed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
        </form>
        <div class="mt-3 flex items-center" style="gap:8px">
            <form method="POST" action="{{ route('admin.orders.fulfillment', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="processing">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Processing</button>
            </form>
            <form method="POST" action="{{ route('admin.orders.fulfillment', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="shipped">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Shipped</button>
            </form>
            <form method="POST" action="{{ route('admin.orders.fulfillment', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ready_for_pickup">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Ready for Pickup</button>
            </form>
            <form method="POST" action="{{ route('admin.orders.fulfillment', $order) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Completed</button>
            </form>
        </div>
        @if($order->pickup_method === 'delivery')
        <div class="mt-6">
            <h3 class="text-md font-semibold mb-2">Shipping Tracking</h3>
            <form method="POST" action="{{ route('admin.orders.tracking', $order) }}" class="flex items-center" style="gap:8px">
                @csrf
                @method('PATCH')
                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Masukkan nomor resi" class="border rounded px-3 py-2 w-64">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Simpan Resi</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
