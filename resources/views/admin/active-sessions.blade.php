@extends('layouts.app')

@section('title','User Active Sessions')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold">User Active Sessions</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-indigo-600">← Kembali ke Dashboard</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="text-gray-700">Total sesi aktif: <span class="font-semibold">{{ $count }}</span></div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-6 border-b">
            <h2 class="text-lg font-bold">Detail Sesi</h2>
        </div>
        <div class="p-6 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-600">
                        <th class="py-2">Nama</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Role</th>
                        <th class="py-2">IP</th>
                        <th class="py-2">User Agent</th>
                        <th class="py-2">Aktivitas Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $s)
                        @php $time = \Carbon\Carbon::createFromTimestamp($s->last_activity); @endphp
                        <tr class="border-t">
                            <td class="py-2">{{ $s->name }}</td>
                            <td class="py-2">{{ $s->email }}</td>
                            <td class="py-2">{{ ucfirst($s->role ?? 'user') }}</td>
                            <td class="py-2">{{ $s->ip_address ?? '-' }}</td>
                            <td class="py-2">{{ Str::limit($s->user_agent ?? '-', 80) }}</td>
                            <td class="py-2">{{ $time->diffForHumans() }} ({{ $time->format('d M Y H:i') }})</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-gray-500">Tidak ada sesi aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection