@extends('layouts.app')

@section('title','Order Issues')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Support tickets</p>
            <h1 class="section-title">Order complaints</h1>
            <p class="section-copy">Pantau komplain pelanggan, SLA respon, dan target penyelesaian.</p>
        </div>

        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Order</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">SLA</th>
                            <th class="px-5 py-3">Response due</th>
                            <th class="px-5 py-3">Resolution due</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($issues as $i)
                            @php
                                $slaClass = $i->sla_status === 'breached'
                                    ? 'bg-rose-100 text-rose-700'
                                    : ($i->sla_status === 'at_risk'
                                        ? 'bg-amber-100 text-amber-800'
                                        : ($i->sla_status === 'met' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700'));
                            @endphp
                            <tr>
                                <td class="px-5 py-4 font-semibold text-slate-950">#{{ optional($i->order)->order_number }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ optional(optional($i->order)->user)->name }}</td>
                                <td class="px-5 py-4 text-slate-700">{{ ucfirst(str_replace('_', ' ', $i->type)) }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst(str_replace('_', ' ', $i->status)) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $slaClass }}">{{ ucfirst(str_replace('_', ' ', $i->sla_status ?? 'on_track')) }}</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $i->response_due_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $i->resolution_due_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.order-issues.show', $i) }}" class="btn btn-secondary">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-5 py-10 text-center text-slate-500">Tidak ada keluhan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $issues->links() }}</div>
    </div>
</div>
@endsection
