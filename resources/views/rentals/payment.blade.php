@extends('layouts.app')

@section('title', 'Pembayaran Sewa')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Payment</p>
                <h1 class="section-title">Pembayaran Sewa</h1>
                <p class="section-copy">Upload bukti transfer untuk mengirim rental ke proses konfirmasi admin.</p>
            </div>
            <a href="{{ route('user.rentals.show', $rental) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Detail rental
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">{{ session('error') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <section class="card p-6">
                <h2 class="text-lg font-semibold text-slate-950">Instruksi Pembayaran</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Transfer sesuai total tagihan, lalu upload bukti pembayaran.</p>
                <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                    <p><span class="font-semibold text-slate-950">Bank BRI:</span> 006801105708509 a/n Andhika</p>
                    <p class="mt-2"><span class="font-semibold text-slate-950">Bank BCA:</span> 2381490019 a/n Andhika</p>
                </div>

                <form method="POST" action="{{ route('rentals.payment.process', $rental) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Upload Bukti Pembayaran</label>
                        <input type="file" name="payment_proof" accept="image/*" class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-slate-800 hover:file:bg-slate-200" required>
                        @error('payment_proof')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Pembayaran</button>
                    </div>
                </form>
            </section>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-950">Ringkasan</h2>
                <dl class="mt-5 space-y-3 text-sm">
                    <div>
                        <dt class="text-slate-500">Tipe</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $rental->type?->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-500">Periode</dt>
                        <dd class="mt-1 font-semibold text-slate-950">{{ $rental->start_time?->format('d M Y H:i') }} - {{ $rental->end_time?->format('d M Y H:i') }}</dd>
                    </div>
                    @if($rental->accessories->isNotEmpty())
                        <div class="border-t border-slate-200 pt-3">
                            <dt class="text-slate-500">Aksesoris</dt>
                            @foreach($rental->accessories as $item)
                                <dd class="mt-2 flex justify-between gap-3 text-slate-700">
                                    <span>{{ $item->accessory?->name }} x{{ $item->quantity }}</span>
                                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                </dd>
                            @endforeach
                        </div>
                    @endif
                    <div class="border-t border-slate-200 pt-3">
                        <dt class="text-slate-500">Total</dt>
                        <dd class="mt-1 text-2xl font-semibold text-slate-950">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>
</div>
@endsection
