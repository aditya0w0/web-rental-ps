@extends('layouts.app')

@section('title', 'Pembayaran Sewa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Pembayaran Sewa</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <div class="text-gray-500 text-sm">Tipe</div>
                <div class="text-lg font-semibold">{{ $rental->type?->name }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Periode</div>
                <div class="text-lg font-semibold">{{ $rental->start_time?->format('d M Y H:i') }} → {{ $rental->end_time?->format('d M Y H:i') }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Total</div>
                <div class="text-2xl font-bold text-emerald-700">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Instruksi Pembayaran</h2>
            <p class="text-gray-600">Silakan transfer total ke rekening berikut lalu upload bukti transfer:</p>
            <ul class="list-disc list-inside mt-2 bg-gray-50 p-4 rounded-md">
                <li><strong>Bank BRI:</strong> 006801105708509 (a/n Andhika)</li>
                <li class="mt-1"><strong>Bank BCA:</strong> 2381490019 (a/n Andhika)</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('rentals.payment.process', $rental) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
                <input type="file" name="payment_proof" accept="image/*" class="mt-1" required>
                @error('payment_proof')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700">Kirim Pembayaran</button>
            </div>
        </form>
    </div>
</div>
@endsection
