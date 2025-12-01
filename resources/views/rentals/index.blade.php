@extends('layouts.app')

@section('title', 'My Rentals')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10 h-fit">
            <x-user-sidebar />
        </aside>
        <div>
            <h1 class="text-3xl font-bold mb-6">My Rentals</h1>

    <div class="card overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Period</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fulfillment</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rentals as $r)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm"><a href="{{ route('user.rentals.show', $r) }}" class="text-indigo-600 hover:underline">{{ $r->type?->name }}</a></td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $r->start_time?->format('d M Y H:i') }} → {{ $r->end_time?->format('d M Y H:i') }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($r->status) }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($r->fulfillment_status ?? 'none') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-500">Belum ada rental.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $rentals->links() }}
        </div>
    </div>
</div>
@endsection