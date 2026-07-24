@extends('layouts.app')

@section('title', $article->title . ' - PlayHub')

@section('content')
@php
    $articleImage = null;
    if (!empty($article->image)) {
        $articleImage = str_starts_with($article->image, 'http')
            ? $article->image
            : (str_starts_with($article->image, 'images/') ? asset($article->image) : asset('storage/' . $article->image));
    }

    $reactionLabels = \App\Models\ArticleReaction::LABELS;
    $reactionIcons = \App\Models\ArticleReaction::ICONS;
@endphp

<article class="bg-white">
    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <a href="{{ route('home') }}#artikel" class="link-action inline-flex w-fit items-center text-sm">
                <i class="fas fa-arrow-left mr-2 text-xs" aria-hidden="true"></i>
                Kembali ke artikel
            </a>

            <div class="mt-7 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span class="status-pill bg-sky-50 text-sky-700">Artikel PlayHub</span>
                <span>{{ $article->author_name ?? 'PlayHub' }}</span>
                <span aria-hidden="true">/</span>
                <time datetime="{{ optional($article->published_at)->toDateString() }}">{{ optional($article->published_at)->format('d M Y') }}</time>
            </div>

            <h1 class="mt-5 max-w-4xl text-3xl font-semibold leading-tight tracking-normal text-slate-950 sm:text-5xl">{{ $article->title }}</h1>
            @if($article->excerpt)
                <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">{{ $article->excerpt }}</p>
            @endif

            @if($articleImage)
                <div class="mt-8 overflow-hidden rounded-lg border border-slate-200 bg-slate-100">
                    <img src="{{ $articleImage }}" alt="{{ $article->title }}" class="aspect-[16/7] h-full w-full object-cover">
                </div>
            @endif
        </div>
    </section>

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:px-8 lg:py-12">
        <div class="min-w-0 lg:pr-8">
            @if(session('success'))
                <div class="mb-8 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="space-y-7 text-base leading-8 text-slate-700 sm:text-lg sm:leading-9">
                @foreach(preg_split("/\r\n|\n|\r/", trim($article->body)) as $paragraph)
                    @if(trim($paragraph) !== '')
                        <p>{{ $paragraph }}</p>
                    @endif
                @endforeach
            </div>
        </div>

        <aside class="lg:sticky lg:top-24 lg:self-start">
            <section class="rounded-lg border border-slate-200 bg-white p-5" aria-labelledby="article-reactions-title">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="section-eyebrow">Reaksi</p>
                        <h2 id="article-reactions-title" class="mt-1 text-lg font-semibold text-slate-950">Beri tanda cepat</h2>
                    </div>
                    <span class="status-pill">{{ $article->reactions_count ?? $reactionCounts->sum() }} total</span>
                </div>

                <div class="mt-4 grid gap-2">
                    @foreach($reactionTypes as $type)
                        @php
                            $isActive = $activeReactions->contains($type);
                        @endphp
                        <form method="POST" action="{{ route('articles.reactions.toggle', $article) }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="flex w-full items-center justify-between rounded-lg border px-4 py-3 text-sm font-semibold transition {{ $isActive ? 'border-sky-500 bg-sky-50 text-sky-800' : 'border-slate-200 bg-white text-slate-700 hover:border-sky-300 hover:bg-sky-50' }}">
                                <span class="inline-flex items-center gap-2">
                                    <i class="fas {{ $reactionIcons[$type] }}" aria-hidden="true"></i>
                                    {{ $reactionLabels[$type] }}
                                </span>
                                <span>{{ $reactionCounts[$type] ?? 0 }}</span>
                            </button>
                        </form>
                    @endforeach
                </div>
            </section>
        </aside>

        <section class="min-w-0 border-t border-slate-200 pt-10 lg:col-span-2" aria-labelledby="comments-title">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="section-eyebrow">Diskusi</p>
                    <h2 id="comments-title" class="mt-1 text-2xl font-semibold text-slate-950">Komentar pembaca</h2>
                </div>
                <span class="status-pill">{{ $comments->count() }} tampil</span>
            </div>

            <form method="POST" action="{{ route('articles.comments.store', $article) }}" class="mt-7 rounded-lg border border-slate-200 bg-white p-5 sm:p-6">
                @csrf

                @guest
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="guest_name" class="block text-sm font-semibold text-slate-700">Nama</label>
                            <input id="guest_name" name="guest_name" type="text" value="{{ old('guest_name', $guestIdentity['name'] ?? '') }}" class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" required>
                            @error('guest_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="guest_email" class="block text-sm font-semibold text-slate-700">Email</label>
                            <input id="guest_email" name="guest_email" type="email" value="{{ old('guest_email', $guestIdentity['email'] ?? '') }}" class="mt-2 block w-full rounded-lg border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500" required>
                            @error('guest_email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                @endguest

                <div class="mt-4">
                    <label for="body" class="block text-sm font-semibold text-slate-700">Tulis komentar</label>
                    <textarea id="body" name="body" rows="4" class="mt-2 block w-full rounded-lg border-slate-300 bg-white text-sm leading-6 focus:border-sky-500 focus:ring-sky-500" required>{{ old('body') }}</textarea>
                    @error('body')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit" class="btn btn-primary w-full sm:w-auto">
                        <i class="fas fa-paper-plane mr-2" aria-hidden="true"></i>
                        Kirim komentar
                    </button>
                </div>
            </form>

            <div class="mt-8 space-y-4">
                @forelse($comments as $comment)
                    <div class="rounded-lg border border-slate-200 bg-white p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="font-semibold text-slate-950">{{ $comment->displayName() }}</div>
                            <time class="text-xs text-slate-500" datetime="{{ $comment->created_at->toDateTimeString() }}">{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $comment->body }}</p>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-slate-300 bg-white px-5 py-10 text-center text-sm text-slate-500">
                        Belum ada komentar yang tampil.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</article>
@endsection
