@extends('layouts.app')

@section('title', 'Create Article - Admin')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Content</p>
            <h1 class="section-title">Artikel baru</h1>
            <p class="section-copy">Isi artikel akan muncul di beranda setelah status publish aktif.</p>
        </div>

        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="card p-6">
            @csrf
            @include('admin.articles.partials.form', ['article' => null])
        </form>
    </div>
</div>
@endsection
