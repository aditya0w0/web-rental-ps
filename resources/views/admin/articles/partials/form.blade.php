@php
    $isEditing = filled($article);
    $publishedDefault = old('is_published', $isEditing ? (int) $article->is_published : 1);
@endphp

<div class="space-y-5">
    <div>
        <label for="title" class="block text-sm font-semibold text-slate-700">Judul</label>
        <input id="title" type="text" name="title" value="{{ old('title', $article->title ?? '') }}" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" required>
        @error('title')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="excerpt" class="block text-sm font-semibold text-slate-700">Ringkasan</label>
        <input id="excerpt" type="text" name="excerpt" value="{{ old('excerpt', $article->excerpt ?? '') }}" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500" maxlength="255">
        @error('excerpt')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="body" class="block text-sm font-semibold text-slate-700">Isi artikel</label>
        <textarea id="body" name="body" rows="12" class="mt-2 block w-full rounded-lg border-slate-300 leading-7 focus:border-sky-500 focus:ring-sky-500" required>{{ old('body', $article->body ?? '') }}</textarea>
        @error('body')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="author_name" class="block text-sm font-semibold text-slate-700">Penulis</label>
            <input id="author_name" type="text" name="author_name" value="{{ old('author_name', $article->author_name ?? auth()->user()->name) }}" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
            @error('author_name')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="published_at" class="block text-sm font-semibold text-slate-700">Tanggal publish</label>
            <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($article?->published_at ?? null)->format('Y-m-d\TH:i')) }}" class="mt-2 block w-full rounded-lg border-slate-300 focus:border-sky-500 focus:ring-sky-500">
            @error('published_at')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="image" class="block text-sm font-semibold text-slate-700">Gambar</label>
        <input id="image" type="file" name="image" accept="image/*" class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-slate-800 hover:file:bg-slate-200">
        @if($isEditing && $article->image)
            <p class="mt-2 text-xs text-slate-500">Gambar saat ini: {{ $article->image }}</p>
        @endif
        @error('image')<p class="mt-2 text-sm text-rose-700">{{ $message }}</p>@enderror
    </div>

    <label class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" name="is_published" value="1" class="rounded border-slate-300 text-slate-950 focus:ring-sky-500" {{ (int) $publishedDefault === 1 ? 'checked' : '' }}>
        Publish ke beranda dan halaman artikel
    </label>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane mr-2" aria-hidden="true"></i>
            Simpan artikel
        </button>
    </div>
</div>
