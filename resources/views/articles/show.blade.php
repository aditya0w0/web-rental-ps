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
@endphp

<article class="bg-white">
    @if($articleImage)
        <div class="h-[320px] overflow-hidden border-b border-slate-200 bg-slate-100 sm:h-[420px]">
            <img src="{{ $articleImage }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
        </div>
    @endif

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}#artikel" class="link-action inline-flex items-center text-sm">
            <i class="fas fa-arrow-left mr-2 text-xs" aria-hidden="true"></i>
            Kembali ke artikel
        </a>

        <header class="mt-6 border-b border-slate-200 pb-8">
            <p class="section-eyebrow">Artikel PlayHub</p>
            <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-normal text-slate-950 sm:text-5xl">{{ $article->title }}</h1>
            @if($article->excerpt)
                <p class="mt-5 text-lg leading-8 text-slate-600">{{ $article->excerpt }}</p>
            @endif
            <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span>{{ $article->author_name ?? 'PlayHub' }}</span>
                <span aria-hidden="true">/</span>
                <time datetime="{{ optional($article->published_at)->toDateString() }}">{{ optional($article->published_at)->format('d M Y') }}</time>
            </div>
        </header>

        <div class="mt-8 space-y-6 text-base leading-8 text-slate-700">
            @foreach(preg_split("/\r\n|\n|\r/", trim($article->body)) as $paragraph)
                @if(trim($paragraph) !== '')
                    <p>{{ $paragraph }}</p>
                @endif
            @endforeach
        </div>
    </div>
</article>
@endsection
