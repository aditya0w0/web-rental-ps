@extends('layouts.app')

@section('title', 'Rental #' . $rental->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Rental Details</h1>
        <a href="{{ route('user.rentals.index') }}" class="link">&larr; Back to My Rentals</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-start gap-6">
            <div class="w-48 h-48 sm:w-56 sm:h-56 flex-shrink-0">
                <img class="w-full h-full object-cover rounded-lg" src="{{ $rental->type?->image_url ?? asset('images/products/playstation-5.png') }}" alt="{{ $rental->type?->name }}"/>
            </div>
            <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Rental Information</h2>
                    <p class="mb-1"><strong>ID:</strong> #{{ $rental->id }}</p>
                    <p class="mb-1"><strong>Type:</strong> {{ $rental->type?->name }}</p>
                    <p class="mb-1"><strong>Unit:</strong> {{ $rental->unit?->unit_code }}</p>
                    <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                    @php $rs=$rental->status; $rmap=['pending'=>'bg-yellow-100 text-yellow-800','active'=>'bg-blue-100 text-blue-800','completed'=>'bg-emerald-100 text-emerald-700','cancelled'=>'bg-red-100 text-red-700']; @endphp
                    <p class="mt-2"><strong>Status:</strong> <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $rmap[$rs] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($rental->status) }}</span></p>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Period</h2>
                    <p class="mb-1"><strong>Start:</strong> {{ $rental->start_time?->format('d M Y H:i') }}</p>
                    <p class="mb-1"><strong>End:</strong> {{ $rental->end_time?->format('d M Y H:i') }}</p>
                    @php $now = \Carbon\Carbon::now(); $end = $rental->end_time; $lateFeeHour = (int) config('service.late_fee_per_hour'); @endphp
                    @if($end && $now->lt($end))
                        <p class="mt-2 text-sm text-amber-700">Sisa waktu: {{ $now->diffForHumans($end, ['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }}</p>
                    @elseif($end && $now->gt($end) && in_array($rental->status, ['active','confirmed']))
                        @php $hoursLate = max(1, (int) ceil($end->diffInMinutes($now)/60)); $est = $hoursLate * $lateFeeHour; @endphp
                        <p class="mt-2 text-sm text-rose-700">Terlambat {{ $hoursLate }} jam. Perkiraan denda: Rp {{ number_format($est,0,',','.') }}</p>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Pickup/Delivery</h2>
                    <p class="mb-1"><strong>Method:</strong> {{ $rental->pickup_method === 'delivery' ? 'Delivery' : 'Store Pickup' }}</p>
                    @if($rental->pickup_method === 'delivery')
                        <p class="mb-1"><strong>Address:</strong> {{ $rental->delivery_address }}</p>
                        <p class="mb-1"><strong>City:</strong> {{ ucfirst($rental->delivery_city) }}</p>
                        <p class="mb-1"><strong>Delivery Fee:</strong> Rp {{ number_format($rental->delivery_fee, 0, ',', '.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Fulfillment Timeline</h2>
        <div class="border-l-2 border-sky-600 pl-4">
            <div class="mb-3">
                <div class="font-semibold">Request Submitted</div>
                <div class="text-sm text-gray-500">{{ $rental->created_at->format('d M Y, H:i') }}</div>
            </div>
            @if($rental->payment_proof)
            <div class="mb-3">
                <div class="font-semibold">Payment Proof Uploaded</div>
            </div>
            @endif
            @if($rental->payment_date)
            <div class="mb-3">
                <div class="font-semibold">Payment Confirmed</div>
                <div class="text-sm text-gray-500">{{ $rental->payment_date->format('d M Y, H:i') }}</div>
            </div>
            @elseif($rental->status === 'cancelled' && $rental->rejection_reason)
            <div class="mb-3">
                <div class="font-semibold">Payment Rejected</div>
                <div class="text-sm text-gray-500">Reason: {{ $rental->rejection_reason }}</div>
            </div>
            @endif
            <div class="mb-3">
                @php $fs = $rental->fulfillment_status ?? 'none'; @endphp
                <div class="font-semibold">Fulfillment:
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                        {{ $fs==='completed' ? 'bg-emerald-100 text-emerald-700' : ($fs==='processing' ? 'bg-indigo-100 text-indigo-700' : ($fs==='ready_for_pickup' ? 'bg-blue-100 text-blue-700' : ($fs==='shipped' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-700'))) }}">
                        {{ ucfirst($fs) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if($rental->accessories->isNotEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Aksesoris Rental</h2>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead><tr><th>Item</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($rental->accessories as $item)
                            <tr>
                                <td>{{ $item->accessory?->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($rental->payment_proof)
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Payment Proof</h2>
            <div class="flex items-start gap-4">
                <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank">
                    <img src="{{ asset('storage/' . $rental->payment_proof) }}" alt="Payment Proof" class="w-32 h-32 object-cover rounded-md shadow-md">
                </a>
                <div class="text-sm text-gray-700">Bukti pembayaran telah diupload. Menunggu konfirmasi admin.</div>
            </div>
        </div>
    @elseif($rental->status === 'pending')
        <a href="{{ route('rentals.payment', $rental) }}" class="bg-green-500 text-white px-6 py-3 rounded-md font-semibold hover:bg-green-600 transition-colors duration-300">Proceed to Payment</a>
    @endif
</div>
@endsection
