@extends('layouts.app')

@section('title', 'Track Order')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10 h-fit">
            <x-user-sidebar />
        </aside>
        <div>
            <div class="max-w-xl card p-6">
                <h1 class="text-2xl font-bold mb-4">Track Order</h1>
                <p class="text-gray-600 mb-6">Masukkan nomor order untuk melihat statusnya.</p>

                <form method="POST" action="{{ route('transaction.track.post') }}" class="flex gap-3">
                    @csrf
                    <input type="text" name="order_number" placeholder="Contoh: ORD-ABC123" class="flex-1 border rounded px-3 py-2" required>
                    <button type="submit" class="btn btn-primary">Cek Status</button>
                </form>
            </div>
            @if(isset($recentOrders) && $recentOrders->count())
                <div class="max-w-xl bg-white shadow rounded-lg p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-3">Order Terakhir Anda</h2>
                    <ul class="divide-y">
                        @foreach($recentOrders as $o)
                            <li class="py-2 flex justify-between items-center">
                                <span>{{ $o->order_number ?? $o->id }} — {{ ucfirst($o->status ?? 'pending') }}</span>
                                <a href="{{ route('orders.show', $o) }}" class="text-indigo-600 hover:underline">Lihat</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
