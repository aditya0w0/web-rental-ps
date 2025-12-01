@extends('layouts.app')

@section('title', 'Order Not Found')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-xl mx-auto bg-white shadow rounded-lg p-8 text-center">
        <h1 class="text-3xl font-bold mb-4">Order Not Found</h1>
        <p class="text-gray-600 mb-6">Kami tidak menemukan order dengan nomor tersebut. Pastikan nomor benar lalu coba lagi.</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('home') }}" class="btn btn-light">Back to Home</a>
            <a href="{{ url('/track') }}" class="btn btn-primary">Coba Nomor Lain</a>
        </div>
    </div>
</div>
@endsection

