@extends('layouts.app')

@section('title', 'Tambah Artikel — Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Tambah Artikel</h1>

    <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-4 max-w-2xl">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded px-3 py-2" required>
            @error('title')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Ringkasan</label>
            <input type="text" name="excerpt" value="{{ old('excerpt') }}" class="mt-1 w-full border rounded px-3 py-2">
            @error('excerpt')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Isi</label>
            <textarea name="body" rows="6" class="mt-1 w-full border rounded px-3 py-2" required>{{ old('body') }}</textarea>
            @error('body')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Penulis</label>
                <input type="text" name="author_name" value="{{ old('author_name') }}" class="mt-1 w-full border rounded px-3 py-2">
                @error('author_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" name="image" class="mt-1 w-full">
                @error('image')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_published" value="1" class="border rounded" checked>
                <span>Publikasikan</span>
            </label>
            <input type="datetime-local" name="published_at" class="border rounded px-2 py-1 text-sm">
            @error('published_at')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div class="pt-2 flex gap-3">
            <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection