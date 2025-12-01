@extends('layouts.app')

@section('title', 'Laporkan Keluhan — Order #' . $order->order_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Laporkan Keluhan</h1>

    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form method="POST" action="{{ route('orders.issue.store', $order) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe Keluhan</label>
                <select name="type" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="damaged">Barang rusak/cacat</option>
                    <option value="wrong_item">Barang tidak sesuai</option>
                    <option value="missing">Barang kurang/hilang</option>
                    <option value="other">Lainnya</option>
                </select>
                @error('type')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="description" rows="4" class="mt-1 w-full border rounded px-3 py-2" placeholder="Jelaskan kondisi kerusakan, waktu terima, dsb" required>{{ old('description') }}</textarea>
                @error('description')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor HP (WhatsApp)</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" placeholder="Contoh: 08xxxxxxxxxx" class="mt-1 w-full border rounded px-3 py-2" required>
                @error('contact_phone')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-500 mt-1">Admin akan menghubungi via WhatsApp untuk tindak lanjut.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Bukti (bisa lebih dari satu)</label>
                <input type="file" name="photos[]" accept="image/*" multiple class="mt-1">
                @error('photos.*')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Link Video (opsional)</label>
                <input type="url" name="video_url" class="mt-1 w-full border rounded px-3 py-2" placeholder="https://...">
                @error('video_url')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3">
                <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Kirim Keluhan</button>
            </div>
            <p class="text-xs text-gray-600">Ajukan keluhan maksimal 24 jam setelah barang diterima. Lampirkan bukti sejelas mungkin.</p>
        </form>
    </div>
</div>
@endsection