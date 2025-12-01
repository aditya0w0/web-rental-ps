@extends('layouts.app')

@section('title', 'Ajukan Sewa — ' . $type->name)

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">Sewa {{ $type->name }}</h1>
        <p class="text-sm text-gray-600 mb-4">Unit tersedia: {{ $availableUnits }}</p>

        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('rent.store', $type) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Mulai</label>
                <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                @error('start_time')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Durasi</label>
                    <input type="number" min="1" name="duration_value" value="{{ old('duration_value', 1) }}" class="mt-1 w-full border rounded px-3 py-2" required>
                    @error('duration_value')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Durasi</label>
                    <select name="duration_type" class="mt-1 w-full border rounded px-3 py-2" required>
                        <option value="hour" {{ old('duration_type')==='hour' ? 'selected' : '' }}>Per Jam</option>
                        <option value="day" {{ old('duration_type')==='day' ? 'selected' : '' }}>Per Hari</option>
                    </select>
                    @error('duration_type')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Metode Pengambilan</label>
                <select name="pickup_method" class="mt-1 w-full border rounded px-3 py-2" required>
                    <option value="store_pickup" {{ old('pickup_method')==='store_pickup' ? 'selected' : '' }}>Ambil di Toko</option>
                    <option value="delivery" {{ old('pickup_method')==='delivery' ? 'selected' : '' }}>Antar ke Alamat</option>
                </select>
                @error('pickup_method')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Alamat (jika antar)</label>
                <textarea name="delivery_address" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('delivery_address') }}</textarea>
                @error('delivery_address')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                <div class="mt-3 flex items-start gap-2 rounded-lg px-3 py-2 border border-amber-400 bg-gradient-to-r from-yellow-50 via-amber-50 to-yellow-100 text-amber-900">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="mt-0.5 flex-shrink-0">
                        <path d="M12 3l9 16H3L12 3z" stroke="#f59e0b" stroke-width="2" fill="#fde68a"/>
                        <path d="M12 10v4" stroke="#92400e" stroke-width="2"/>
                        <circle cx="12" cy="17" r="1" fill="#92400e"/>
                    </svg>
                    <span class="text-sm font-medium">Pengiriman hanya menjangkau kota Batang, Pekalongan, dan Pemalang.</span>
                </div>
                <div class="mt-3 flex items-start gap-2 rounded-lg px-3 py-2 border border-rose-300 bg-gradient-to-r from-rose-50 via-red-50 to-rose-100 text-rose-900">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="mt-0.5 flex-shrink-0"><circle cx="12" cy="12" r="10" stroke="#e11d48" stroke-width="2"/><path d="M12 7v6" stroke="#b91c1c" stroke-width="2"/><circle cx="12" cy="16" r="1" fill="#b91c1c"/></svg>
                    <span class="text-sm font-medium">Denda keterlambatan Rp {{ number_format(config('service.late_fee_per_hour'),0,',','.') }} per jam. Harap kembalikan tepat waktu.</span>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kota (jika antar)</label>
                <select name="delivery_city" class="mt-1 w-full border rounded px-3 py-2">
                    <option value="">Pilih Kota</option>
                    @foreach(config('service.allowed_cities') as $city)
                        <option value="{{ strtolower($city) }}" {{ old('delivery_city')===strtolower($city) ? 'selected' : '' }}>{{ ucfirst($city) }}</option>
                    @endforeach
                </select>
                @error('delivery_city')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-700 mt-1">Ongkir otomatis per kota: Batang Rp 15.000, Pekalongan Rp 10.000, Pemalang Rp 20.000.</p>
            </div>

            

            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 w-full border rounded px-3 py-2" required>
                @error('phone_number')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea name="notes" class="mt-1 w-full border rounded px-3 py-2" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2 flex gap-3">
                <a href="{{ route('products.playstation') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="agree_terms" value="1" class="border rounded">
                    <span>Saya telah membaca dengan benar dan setuju</span>
                </label>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Ajukan Sewa</button>
            </div>
            <p class="text-xs text-gray-600 mt-2">*Syarat: KTP diserahkan jika barang telah diambil/diterima.</p>
        </form>
    </div>
</div>
@endsection

<script></script>

