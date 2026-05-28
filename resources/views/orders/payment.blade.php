@extends('layouts.app')

@section('title', 'Payment for Order #' . $order->order_number)

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Payment</p>
                <h1 class="section-title">Bayar order {{ $order->order_number }}</h1>
                <p class="section-copy">Upload bukti transfer. Setelah submit, halaman akan kembali ke detail order.</p>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Detail order
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <section class="space-y-6">
                <div class="card p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">Instruksi transfer</h2>
                            <p class="mt-1 text-sm text-slate-600">Transfer sesuai total order agar admin bisa validasi tanpa revisi.</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-right">
                            <div class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total bayar</div>
                            <div class="mt-1 text-2xl font-bold text-slate-950">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="text-sm font-semibold text-slate-500">Bank BRI</div>
                            <div class="mt-2 text-xl font-bold text-slate-950">006801105708509</div>
                            <div class="mt-1 text-sm text-slate-600">a/n Andhika</div>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-white p-4">
                            <div class="text-sm font-semibold text-slate-500">Bank BCA</div>
                            <div class="mt-2 text-xl font-bold text-slate-950">2381490019</div>
                            <div class="mt-1 text-sm text-slate-600">a/n Andhika</div>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-slate-950">Isi order</h2>
                    <div class="mt-5 space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                <div>
                                    <div class="font-semibold text-slate-950">{{ $item->accessory->name }}</div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                </div>
                                <div class="font-semibold text-slate-950">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                            </div>
                        @endforeach

                        @if(($order->shipping_cost ?? 0) > 0)
                            <div class="flex items-start justify-between gap-4 border-t border-slate-200 pt-4">
                                <div>
                                    <div class="font-semibold text-slate-950">Ongkir</div>
                                    <div class="mt-1 text-sm text-slate-500">{{ ucfirst($order->delivery_city) }}</div>
                                </div>
                                <div class="font-semibold text-slate-950">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <h2 class="text-lg font-semibold text-slate-950">Upload bukti</h2>
                <p class="mt-1 text-sm text-slate-600">Gunakan gambar JPG, PNG, atau GIF maksimal 2MB.</p>

                <form action="{{ route('orders.payment.process', $order) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="payment_proof" class="block text-sm font-semibold text-slate-700">Bukti transfer</label>
                        <input type="file" name="payment_proof" id="payment_proof" class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 text-sm text-slate-900 file:mr-4 file:border-0 file:bg-slate-950 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-upload mr-2" aria-hidden="true"></i>
                        Upload & lihat order
                    </button>
                </form>
            </aside>
        </div>
    </div>
</div>
@endsection
