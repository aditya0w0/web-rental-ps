@extends('layouts.app')

@section('title', 'Laporkan Keluhan - Order #' . $order->order_number)

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Complaint ticket</p>
                <h1 class="section-title">Laporkan keluhan</h1>
                <p class="section-copy">Order {{ $order->order_number }} akan masuk antrian admin dengan SLA respon {{ config('service.complaint_sla.response_hours', 4) }} jam.</p>
            </div>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Detail order
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <section class="card p-6">
                <form method="POST" action="{{ route('orders.issue.store', $order) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Tipe keluhan</label>
                        <select name="type" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="damaged" @selected(old('type') === 'damaged')>Barang rusak/cacat</option>
                            <option value="wrong_item" @selected(old('type') === 'wrong_item')>Barang tidak sesuai</option>
                            <option value="missing" @selected(old('type') === 'missing')>Barang kurang/hilang</option>
                            <option value="other" @selected(old('type') === 'other')>Lainnya</option>
                        </select>
                        @error('type')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
                        <textarea name="description" rows="5" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" placeholder="Jelaskan kondisi barang, waktu diterima, dan bantuan yang kamu butuhkan." required>{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Nomor HP (WhatsApp)</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" placeholder="08xxxxxxxxxx" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                        @error('contact_phone')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Foto bukti</label>
                        <input type="file" name="photos[]" accept="image/*" multiple class="mt-2 block w-full rounded-lg border border-slate-300 bg-slate-50 text-sm text-slate-900 file:mr-4 file:border-0 file:bg-slate-950 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-white focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        @error('photos.*')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Link video</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" placeholder="https://...">
                        @error('video_url')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-wrap gap-3 pt-2">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim keluhan</button>
                    </div>
                </form>
            </section>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-950">SLA ticket</h2>
                <dl class="mt-5 space-y-4 text-sm">
                    <div>
                        <dt class="font-semibold text-slate-500">Respon admin</dt>
                        <dd class="mt-1 text-slate-950">{{ config('service.complaint_sla.response_hours', 4) }} jam setelah ticket dibuat</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Target selesai</dt>
                        <dd class="mt-1 text-slate-950">{{ config('service.complaint_sla.resolution_hours', 48) }} jam setelah ticket dibuat</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-500">Window komplain</dt>
                        <dd class="mt-1 text-slate-950">Maksimal 24 jam setelah barang diterima.</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>
</div>
@endsection
