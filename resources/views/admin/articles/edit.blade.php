@extends('layouts.app')

@section('title', 'Edit Article - Admin')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8">
            <p class="section-eyebrow">Content</p>
            <h1 class="section-title">Edit artikel</h1>
            <p class="section-copy">Update draft atau publish artikel untuk ditampilkan ke pelanggan.</p>
        </div>

        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="card p-6">
            @csrf
            @method('PUT')
            @include('admin.articles.partials.form', ['article' => $article])
        </form>
    </div>
</div>
@endsection
