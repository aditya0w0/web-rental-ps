@extends('layouts.app')

@section('title', 'Rentals — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">All Rentals</h1>

    <div class="card overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">ID</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Period</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentals as $r)
                    <tr class="border-b">
                        <td class="px-5 py-4 text-sm">#{{ $r->id }}</td>
                        <td class="px-5 py-4 text-sm">{{ $r->user?->name }}</td>
                        <td class="px-5 py-4 text-sm">{{ $r->type?->name }}</td>
                        <td class="px-5 py-4 text-sm">{{ $r->start_time?->format('d M Y H:i') }} → {{ $r->end_time?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-4 text-sm">Rp {{ number_format($r->total_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-sm">{{ ucfirst($r->status) }}</td>
                        <td class="px-5 py-4 text-sm text-right">
                            <div class="inline-flex items-center" style="gap:8px">
                                <a href="{{ route('admin.rentals.show', $r) }}" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded">Lihat</a>
                                <form method="POST" action="{{ route('admin.rentals.confirm', $r) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2 py-1 bg-green-50 text-green-700 rounded">Confirm</button>
                                </form>
                                <form method="POST" action="{{ route('admin.rentals.reject', $r) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="reason" value="Rejected from list">
                                    <button type="submit" class="px-2 py-1 bg-red-50 text-red-700 rounded">Reject</button>
                                </form>
                                @if($r->status === 'confirmed')
                                    <form method="POST" action="{{ route('admin.rentals.set-active', $r) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2 py-1 bg-blue-50 text-blue-700 rounded">Set Active</button>
                                    </form>
                                @endif
                                @if(in_array($r->status, ['confirmed','active']))
                                    <form method="POST" action="{{ route('admin.rentals.set-completed', $r) }}" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded">Set Completed</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-500">Belum ada rental.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $rentals->links() }}
    </div>
</div>
@endsection

