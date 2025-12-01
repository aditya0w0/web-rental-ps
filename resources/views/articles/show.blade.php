@extends('layouts.app')

@section('title', $article->title ?? 'Artikel')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg overflow-hidden">
        @if(!empty($article->image))
            <img src="{{ str_starts_with($article->image, 'http') ? $article->image : asset('storage/'.$article->image) }}" alt="{{ $article->title }}" class="w-full h-64 object-cover">
        @endif
        <div class="p-6">
            <h1 class="text-3xl font-bold mb-2">{{ $article->title }}</h1>
            <div class="text-sm text-gray-500 mb-6">{{ $article->author_name ?? 'PlayHub' }} • {{ optional($article->published_at)->format('d M Y') }}</div>
            <div class="prose max-w-none">{!! nl2br(e($article->body)) !!}</div>
        </div>
    </div>
</div>
@endsection