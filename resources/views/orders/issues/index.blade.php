@extends('layouts.app')

@section('title', 'Keluhan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">
        <aside class="hidden lg:block bg-white/10 rounded-lg p-4 border border-white/10 h-fit">
            <x-user-sidebar />
        </aside>
        <div>
            <h1 class="text-3xl font-bold mb-6">Keluhan Saya</h1>

            <div class="card overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-green-50 text-left text-xs font-semibold text-emerald-700 uppercase tracking-wider">Order</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipe</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dibuat</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Respon Admin</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($issues as $issue)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">#{{ optional($issue->order)->order_number }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($issue->type) }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($issue->status) }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $issue->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $issue->admin_response ? Str::limit($issue->admin_response, 80) : '-' }}</td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    @if($issue->order)
                                        <a href="{{ route('orders.show', $issue->order) }}" class="text-indigo-600 hover:text-indigo-900">Lihat Order</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 text-gray-500">Tidak ada keluhan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $issues->links() }}
            </div>
        </div>
    </div>
</div>
@endsection