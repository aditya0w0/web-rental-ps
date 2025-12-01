@extends('layouts.app')

@section('title', 'Invoice - '.$transaction->transaction_code)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="soft-gradient text-white px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">PlayHub Invoice</h1>
                <p class="text-sm opacity-90">Sistem Informasi Rental PlayStation & Penjualan Aksesoris</p>
            </div>
            <div class="text-right">
                <div class="text-sm">Kode Transaksi</div>
                <div class="text-xl font-semibold">{{ $transaction->transaction_code }}</div>
            </div>
        </div>

        <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-b">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Pelanggan</h2>
                <div class="text-gray-700">
                    <div class="font-medium">{{ $transaction->user->name ?? '-' }}</div>
                    <div class="text-sm">{{ $transaction->user->email ?? '-' }}</div>
                    @if($transaction->delivery_address)
                        <div class="text-sm mt-1">Alamat: {{ $transaction->delivery_address }}</div>
                    @endif
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Detail Transaksi</h2>
                <div class="text-gray-700 text-sm space-y-1">
                    <div>Jenis: <span class="font-medium">{{ $transaction->type_text }}</span></div>
                    <div>Metode Bayar: <span class="font-medium">{{ strtoupper(str_replace('_',' ', $transaction->payment_method)) }}</span></div>
                    <div>Status Bayar: 
                        <span class="badge {{ $transaction->isPaid() ? 'badge-success' : 'badge-warning' }}">
                            {{ $transaction->payment_status_text }}
                        </span>
                    </div>
                    @if($transaction->payment_date)
                        <div>Tgl Bayar: <span class="font-medium">{{ $transaction->payment_date->format('d M Y H:i') }}</span></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Item</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">Deskripsi</th>
                            <th class="py-2">Tipe</th>
                            <th class="py-2">Qty</th>
                            <th class="py-2">Harga</th>
                            <th class="py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaction->items as $item)
                            <tr class="border-t">
                                <td class="py-2">{{ $item->item_name }}</td>
                                <td class="py-2">{{ ucfirst($item->item_type) }}</td>
                                <td class="py-2">{{ $item->quantity }}</td>
                                <td class="py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-2 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Belum ada item.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-6 py-4 border-t flex items-center justify-between">
            <div class="text-sm text-gray-600">
                @if($transaction->payment_method === 'bank_transfer')
                    Transfer ke rekening: <span class="font-medium">BCA 123456789 a/n PlayHub</span>
                @elseif($transaction->payment_method === 'qris')
                    Silakan scan QRIS pada halaman pembayaran.
                @endif
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Total</div>
                <div class="text-2xl font-bold text-emerald-700">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('orders.index') }}" class="btn btn-light">Kembali ke Pesanan</a>
        @if(!$transaction->isPaid())
            <a href="{{ route('orders.payment', $transaction->id) }}" class="btn btn-primary">Lanjutkan Pembayaran</a>
        @endif
    </div>
</div>
@endsection