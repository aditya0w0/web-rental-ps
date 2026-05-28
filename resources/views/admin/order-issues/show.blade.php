@extends('layouts.app')

@section('title','Order Issue #' . $issue->id)

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @php
            $slaClass = $issue->sla_status === 'breached'
                ? 'bg-rose-100 text-rose-700'
                : ($issue->sla_status === 'at_risk'
                    ? 'bg-amber-100 text-amber-800'
                    : ($issue->sla_status === 'met' ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700'));
        @endphp

        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Support ticket</p>
                <h1 class="section-title">Complaint #{{ $issue->id }}</h1>
                <p class="section-copy">Order {{ optional($issue->order)->order_number }} dari {{ optional(optional($issue->order)->user)->name }}.</p>
            </div>
            <a href="{{ route('admin.order-issues.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i>
                Semua ticket
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <section class="space-y-6">
                <div class="card p-6">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">{{ ucfirst(str_replace('_', ' ', $issue->status)) }}</span>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $slaClass }}">{{ ucfirst(str_replace('_', ' ', $issue->sla_status ?? 'on_track')) }}</span>
                    </div>

                    <dl class="mt-6 grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Type</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ ucfirst(str_replace('_', ' ', $issue->type)) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Contact</dt>
                            <dd class="mt-1 flex flex-wrap items-center gap-2 font-semibold text-slate-950">
                                <span>{{ $issue->contact_phone ?? '-' }}</span>
                                @if($issue->contact_phone)
                                    <a target="_blank" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$issue->contact_phone) }}?text={{ urlencode('Halo, terkait keluhan order #' . optional($issue->order)->order_number) }}" class="btn btn-secondary py-2">Chat WA</a>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Response due</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $issue->response_due_at?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Resolution due</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $issue->resolution_due_at?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">First response</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $issue->first_responded_at?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-semibold text-slate-500">Resolved at</dt>
                            <dd class="mt-1 font-semibold text-slate-950">{{ $issue->resolved_at?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="card p-6">
                    <h2 class="text-lg font-semibold text-slate-950">Customer report</h2>
                    <div class="mt-4 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $issue->description }}</div>

                    @if($issue->photos && $issue->photos->count())
                        <div class="mt-6 grid grid-cols-2 gap-3 md:grid-cols-3">
                            @foreach($issue->photos as $p)
                                <a href="{{ asset('storage/' . $p->path) }}" target="_blank" class="block overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                                    <img src="{{ asset('storage/' . $p->path) }}" alt="Complaint photo" class="h-36 w-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    @elseif($issue->photo_path)
                        <a href="{{ asset('storage/' . $issue->photo_path) }}" target="_blank" class="mt-6 block max-w-xs overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                            <img src="{{ asset('storage/' . $issue->photo_path) }}" alt="Complaint photo" class="h-auto w-full">
                        </a>
                    @endif

                    @if($issue->video_url)
                        <div class="mt-5">
                            <a href="{{ $issue->video_url }}" target="_blank" class="link">Buka video bukti</a>
                        </div>
                    @endif
                </div>
            </section>

            <aside class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-24">
                <h2 class="text-lg font-semibold text-slate-950">Admin response</h2>
                <form method="POST" action="{{ route('admin.order-issues.respond', $issue) }}" class="mt-5 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Response</label>
                        <textarea name="admin_response" rows="5" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>{{ old('admin_response', $issue->admin_response) }}</textarea>
                        @error('admin_response')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Status</label>
                        <select name="status" class="mt-2 w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
                            <option value="open" @selected($issue->status==='open')>Open</option>
                            <option value="in_progress" @selected($issue->status==='in_progress')>In progress</option>
                            <option value="resolved" @selected($issue->status==='resolved')>Resolved</option>
                            <option value="rejected" @selected($issue->status==='rejected')>Rejected</option>
                        </select>
                        @error('status')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full">Save response</button>
                </form>
            </aside>
        </div>
    </div>
</div>
@endsection
