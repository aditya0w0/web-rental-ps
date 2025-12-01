@extends('layouts.app')

@section('title', 'Buy Accessories')

@section('content')
<div class="bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800">Accessories</h1>
            <p class="text-lg text-gray-600 mt-2">Browse our collection of PlayStation accessories.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @forelse($accessories as $accessory)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ $accessory->image ? asset('storage/' . $accessory->image) : 'https://via.placeholder.com/400x300' }}" alt="{{ $accessory->name }}" class="w-full h-56 object-cover">
                        <div class="absolute top-0 left-0 bg-yellow-500 text-white px-3 py-1 m-2 rounded-md text-sm font-semibold">{{ $accessory->category }}</div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 h-16">{{ $accessory->name }}</h3>
                        <p class="text-sm text-gray-500 mb-2">Brand: {{ $accessory->brand ?? 'N/A' }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-lg font-bold text-purple-600">Rp {{ number_format($accessory->price, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600">Stock: {{ $accessory->stock }}</p>
                        </div>

                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="accessory_id" value="{{ $accessory->id }}">
                            <button type="submit" class="w-full flex items-center justify-center bg-purple-600 text-white py-2 rounded-md font-semibold hover:bg-purple-700 transition-colors duration-300">
                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-xl text-gray-500">No accessories available at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $accessories->links() }}
        </div>
    </div>
</div>
@endsection