@extends('layouts.app')

@section('title', 'Rental #'.$rental->id.' — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Rental #{{ $rental->id }}</h1>
        <a href="{{ route('admin.rentals.index') }}" class="link">&larr; Back</a>
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
                <div class="font-semibold">{{ $rental->user?->name }}</div>
                <div class="text-sm text-gray-600">{{ $rental->user?->email }}</div>
                @php $phone = $rental->phone_number ?? optional($rental->user)->phone; @endphp
                @if($phone)
                    <div class="mt-1 text-sm text-gray-600"><span class="font-semibold">Phone:</span> {{ $phone }}
                        <a target="_blank" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$phone) }}" class="ml-2 text-emerald-600">WA</a>
                    </div>
                @endif
            </div>
            <div>
                <div class="text-gray-500 text-sm">Type</div>
                <div class="font-semibold">{{ $rental->type?->name }}</div>
                <div class="text-gray-500 text-sm mt-2">Unit: {{ $rental->unit?->unit_code }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Period</div>
                <div class="font-semibold">{{ $rental->start_time?->format('d M Y H:i') }} → {{ $rental->end_time?->format('d M Y H:i') }}</div>
                <div class="text-gray-500 text-sm mt-2">Total: Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                <div class="text-gray-500 text-sm mt-1">Status: {{ ucfirst($rental->status) }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Pickup/Delivery</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-gray-500 text-sm">Method</div>
                <div class="font-semibold">{{ $rental->pickup_method === 'delivery' ? 'Delivery' : 'Store Pickup' }}</div>
            </div>
            @if($rental->pickup_method === 'delivery')
                <div>
                    <div class="text-gray-500 text-sm">Address</div>
                    <div class="font-semibold">{{ $rental->delivery_address }}</div>
                    <div class="mt-3 text-gray-500 text-sm">City</div>
                    <div class="font-semibold">{{ ucfirst($rental->delivery_city) }}</div>
                    <div class="mt-3 text-gray-500 text-sm">Delivery Fee</div>
                    <div class="font-semibold">Rp {{ number_format($rental->delivery_fee, 0, ',', '.') }}</div>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Payment</h2>
        @if($rental->payment_proof ?? false)
            <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank">
                <img src="{{ asset('storage/' . $rental->payment_proof) }}" alt="Payment Proof" class="w-32 h-32 object-cover rounded-md shadow">
            </a>
        @else
            <div class="text-sm text-gray-600">No payment proof uploaded.</div>
        @endif

        <div class="flex gap-3 mt-4 items-center">
            <form method="POST" action="{{ route('admin.rentals.confirm', $rental) }}" class="flex items-center">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Confirm Payment</button>
            </form>
            <form method="POST" action="{{ route('admin.rentals.reject', $rental) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="text" name="reason" placeholder="Alasan reject" class="border rounded px-3 py-2" required>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Reject</button>
            </form>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">Fulfillment</h2>
        <div class="mb-2">Status: <span class="font-semibold">{{ ucfirst($rental->fulfillment_status ?? 'none') }}</span></div>
        <div class="flex gap-3 mb-4">
            @if($rental->status === 'confirmed')
                <form method="POST" action="{{ route('admin.rentals.set-active', $rental) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Set Active</button>
                </form>
                <form method="POST" action="{{ route('admin.rentals.set-completed', $rental) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Set Completed</button>
                </form>
            @endif
        </div>
        <form method="POST" action="{{ route('admin.rentals.fulfillment', $rental) }}" class="flex items-center" style="gap:8px">
            @csrf
            @method('PATCH')
            <select name="status" class="border rounded px-3 py-2">
                <option value="none" {{ ($rental->fulfillment_status ?? 'none')==='none'?'selected':'' }}>None</option>
                <option value="processing" {{ $rental->fulfillment_status==='processing'?'selected':'' }}>Processing</option>
                <option value="delivering" {{ $rental->fulfillment_status==='delivering'?'selected':'' }}>Delivering</option>
                <option value="ready_for_pickup" {{ $rental->fulfillment_status==='ready_for_pickup'?'selected':'' }}>Ready for Pickup</option>
                <option value="completed" {{ $rental->fulfillment_status==='completed'?'selected':'' }}>Completed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
        </form>
        <div class="mt-3 flex items-center" style="gap:8px">
            <form method="POST" action="{{ route('admin.rentals.fulfillment', $rental) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="processing">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Processing</button>
            </form>
            <form method="POST" action="{{ route('admin.rentals.fulfillment', $rental) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="delivering">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Delivering</button>
            </form>
            <form method="POST" action="{{ route('admin.rentals.fulfillment', $rental) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="ready_for_pickup">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Ready for Pickup</button>
            </form>
            <form method="POST" action="{{ route('admin.rentals.fulfillment', $rental) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="px-2 py-1 bg-gray-100 text-gray-700 rounded">Set Completed</button>
            </form>
        </div>
    </div>
</div>
@endsection

