@extends('layouts.app')

@section('title', 'Articles - Admin')

@section('content')
<div class="bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="section-eyebrow">Content</p>
                <h1 class="section-title">Artikel</h1>
                <p class="section-copy">Tulis, simpan draft, dan publish artikel yang tampil di beranda.</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-2" aria-hidden="true"></i>
                Artikel baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        <section class="table-shell">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Published</th>
                            <th>Penulis</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-950">{{ $article->title }}</div>
                                    <div class="mt-1 max-w-xl text-xs leading-5 text-slate-500">{{ $article->excerpt ?: 'Tanpa ringkasan.' }}</div>
                                </td>
                                <td>
                                    <span class="badge {{ $article->is_published ? 'badge-success' : 'badge-warning' }}">
                                        {{ $article->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>{{ $article->published_at?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $article->author_name ?? '-' }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        @if($article->is_published)
                                            <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-secondary px-3 py-1.5 text-xs">View</a>
                                        @endif
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-secondary px-3 py-1.5 text-xs">Edit</a>
                                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn border border-rose-200 bg-white px-3 py-1.5 text-xs text-rose-700 hover:bg-rose-50">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-slate-500">Belum ada artikel.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 px-5 py-4">{{ $articles->links() }}</div>
        </section>
    </div>
</div>
@endsection
