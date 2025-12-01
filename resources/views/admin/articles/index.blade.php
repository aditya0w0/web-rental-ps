@extends('layouts.app')

@section('title', 'Articles — Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Kelola Artikel</h1>
        <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded">Tambah Artikel</a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left text-gray-600">
                    <th class="py-3 px-4">Judul</th>
                    <th class="py-3 px-4">Dipublikasikan</th>
                    <th class="py-3 px-4">Penulis</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $a)
                    <tr class="border-t">
                        <td class="py-2 px-4 font-medium">{{ $a->title }}</td>
                        <td class="py-2 px-4">{{ $a->published_at?->format('d M Y') }}</td>
                        <td class="py-2 px-4">{{ $a->author_name ?? '-' }}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <a href="{{ route('admin.articles.edit', $a) }}" class="px-3 py-1 bg-gray-200 rounded">Edit</a>
                            <form method="POST" action="{{ route('admin.articles.destroy', $a) }}" onsubmit="return confirm('Hapus artikel?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-gray-500">Belum ada artikel</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $articles->links() }}</div>
    </div>
</div>
@endsection