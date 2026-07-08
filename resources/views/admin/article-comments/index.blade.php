@extends('layouts.app')

@section('title', 'Article Comments - Admin')

@section('content')
<div class="bg-slate-50 py-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Moderasi</p>
                <h1 class="section-title">Komentar artikel</h1>
                <p class="section-copy">Guest masuk lebih ketat, user login lebih longgar, dan semua aksi admin tetap meninggalkan jejak.</p>
            </div>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary w-fit">
                <i class="fas fa-newspaper mr-2" aria-hidden="true"></i>
                Artikel
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-5 flex flex-wrap gap-2">
            @php
                $filters = [
                    '' => 'Semua',
                    \App\Models\ArticleComment::STATUS_PENDING => 'Pending',
                    \App\Models\ArticleComment::STATUS_APPROVED => 'Approved',
                    \App\Models\ArticleComment::STATUS_FLAGGED => 'Flagged',
                    \App\Models\ArticleComment::STATUS_DELETED => 'Deleted',
                ];
            @endphp
            @foreach($filters as $value => $label)
                <a href="{{ route('admin.article-comments.index', array_filter(['status' => $value])) }}" class="btn {{ ($status ?? '') === $value ? 'btn-primary' : 'btn-secondary' }} py-2 text-sm">
                    {{ $label }}
                    @if($value !== '')
                        <span class="ml-2 rounded-full bg-white/20 px-2 text-xs">{{ $counts[$value] ?? 0 }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Komentar</th>
                        <th>Artikel</th>
                        <th>Status</th>
                        <th>Jejak admin</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td class="align-top">
                                <div class="font-semibold text-slate-950">{{ $comment->displayName() }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $comment->guest_email ?: ($comment->user?->email ?? 'User login') }}</div>
                                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-700">{{ $comment->body }}</p>
                            </td>
                            <td class="align-top">
                                @if($comment->article)
                                    <a href="{{ route('articles.show', $comment->article->slug) }}" class="link-action">{{ $comment->article->title }}</a>
                                @else
                                    <span class="text-slate-500">Artikel hilang</span>
                                @endif
                                <div class="mt-1 text-xs text-slate-500">{{ $comment->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="align-top">
                                <span class="badge {{ $comment->status === 'approved' ? 'badge-success' : ($comment->status === 'deleted' ? 'badge-danger' : 'badge-warning') }}">{{ ucfirst($comment->status) }}</span>
                            </td>
                            <td class="align-top text-xs leading-5 text-slate-500">
                                @if($comment->moderator)
                                    <div>{{ $comment->moderator->name }}</div>
                                    <div>{{ optional($comment->moderated_at)->format('d M Y H:i') }}</div>
                                @else
                                    <div>Belum ada aksi admin</div>
                                @endif
                                @if($comment->moderation_note)
                                    <div class="mt-1 text-slate-700">{{ $comment->moderation_note }}</div>
                                @endif
                            </td>
                            <td class="align-top">
                                <div class="flex flex-col gap-2 sm:items-end">
                                    @if($comment->status !== \App\Models\ArticleComment::STATUS_APPROVED && $comment->status !== \App\Models\ArticleComment::STATUS_DELETED)
                                        <form method="POST" action="{{ route('admin.article-comments.approve', $comment) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-primary py-2 text-xs" type="submit">Approve</button>
                                        </form>
                                    @endif

                                    @if($comment->status !== \App\Models\ArticleComment::STATUS_DELETED)
                                        <form method="POST" action="{{ route('admin.article-comments.flag', $comment) }}" class="flex max-w-xs gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="moderation_note" placeholder="Catatan" class="w-32 rounded-lg border-slate-300 text-xs focus:border-sky-500 focus:ring-sky-500">
                                            <button class="btn btn-secondary py-2 text-xs" type="submit">Flag</button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.article-comments.destroy-trace', $comment) }}" class="flex max-w-xs gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="text" name="moderation_note" placeholder="Alasan hapus" class="w-32 rounded-lg border-slate-300 text-xs focus:border-sky-500 focus:ring-sky-500">
                                            <button class="btn btn-secondary py-2 text-xs" type="submit">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-slate-500">Belum ada komentar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5">{{ $comments->links() }}</div>
    </div>
</div>
@endsection
