@extends('layouts.app')

@section('title','Order Issues')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Order Issues</h1>
    <div class="bg-white shadow rounded-lg p-6">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-2">Order</th>
                    <th class="py-2">Customer</th>
                    <th class="py-2">Type</th>
                    <th class="py-2">Status</th>
                    <th class="py-2">Deadline</th>
                    <th class="py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($issues as $i)
                <tr class="border-t">
                    <td class="py-2">#{{ optional($i->order)->order_number }}</td>
                    <td class="py-2">{{ optional(optional($i->order)->user)->name }}</td>
                    <td class="py-2">{{ ucfirst($i->type) }}</td>
                    <td class="py-2">{{ ucfirst($i->status) }}</td>
                    @if(optional($i->order)->delivered_at)
                        @php $deadline = optional($i->order)->delivered_at->copy()->addHours(24); @endphp
                        <td class="py-2 text-xs text-gray-500">Deadline: {{ $deadline->format('d M Y H:i') }}</td>
                    @else
                        <td class="py-2 text-xs text-gray-400">Deadline: -</td>
                    @endif
                    <td class="py-2"><a href="{{ route('admin.order-issues.show', $i) }}" class="text-indigo-600">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-gray-500">Tidak ada keluhan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">{{ $issues->links() }}</div>
    </div>
</div>
@endsection