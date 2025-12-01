@extends('layouts.app')

@section('title','Order Issue #' . $issue->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Order Issue Detail</h1>
    <div class="bg-white shadow rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="text-sm text-gray-500">Order</div>
            <div class="font-semibold">#{{ optional($issue->order)->order_number }}</div>
            <div class="mt-3 text-sm text-gray-500">Customer</div>
            <div class="font-semibold">{{ optional(optional($issue->order)->user)->name }}</div>
            <div class="mt-3 text-sm text-gray-500">Type</div>
            <div class="font-semibold">{{ ucfirst($issue->type) }}</div>
            <div class="mt-3 text-sm text-gray-500">Contact Phone</div>
            <div class="flex items-center gap-2">
                <span class="font-semibold">{{ $issue->contact_phone ?? '-' }}</span>
                @if($issue->contact_phone)
                    <a target="_blank" href="https://wa.me/{{ preg_replace('/[^0-9]/','',$issue->contact_phone) }}?text={{ urlencode('Halo, terkait keluhan order #' . optional($issue->order)->order_number) }}" class="px-2 py-1 rounded bg-emerald-600 text-white">Chat WA</a>
                @endif
            </div>
            <div class="mt-3 text-sm text-gray-500">Description</div>
            <div class="whitespace-pre-line">{{ $issue->description }}</div>
            @if($issue->photo_path)
            <div class="mt-3 text-sm text-gray-500">Photo</div>
            <div><img src="{{ asset('storage/' . $issue->photo_path) }}" alt="photo" class="w-64 h-auto rounded"></div>
            @endif
            @if($issue->photos && $issue->photos->count())
            <div class="mt-3 text-sm text-gray-500">Photos</div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($issue->photos as $p)
                    <img src="{{ asset('storage/' . $p->path) }}" alt="photo" class="w-full h-auto rounded">
                @endforeach
            </div>
            @endif
            @if($issue->video_url)
            <div class="mt-3 text-sm text-gray-500">Video</div>
            <div><a href="{{ $issue->video_url }}" target="_blank" class="text-indigo-600">{{ $issue->video_url }}</a></div>
            @endif
        </div>
        <div>
            <form method="POST" action="{{ route('admin.order-issues.respond', $issue) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Response</label>
                    <textarea name="admin_response" rows="4" class="mt-1 w-full border rounded px-3 py-2" required>{{ old('admin_response', $issue->admin_response) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 w-full border rounded px-3 py-2" required>
                        <option value="open" @selected($issue->status==='open')>Open</option>
                        <option value="in_progress" @selected($issue->status==='in_progress')>In Progress</option>
                        <option value="resolved" @selected($issue->status==='resolved')>Resolved</option>
                        <option value="rejected" @selected($issue->status==='rejected')>Rejected</option>
                    </select>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.order-issues.index') }}" class="px-4 py-2 bg-gray-200 rounded">Back</a>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection