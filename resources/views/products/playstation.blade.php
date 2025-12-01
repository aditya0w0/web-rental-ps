@extends('layouts.app')

@section('title', 'Rent PlayStation')

@section('content')
<div class="bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-800">Rent a PlayStation</h1>
            <p class="text-lg text-gray-600 mt-2">Choose from our available PlayStation consoles.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($playstationTypes as $type)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <img src="{{ $type->image ? asset('storage/' . $type->image) : 'https://via.placeholder.com/400x300' }}" alt="{{ $type->name }}" class="w-full h-64 object-cover">
                        <div class="absolute top-0 right-0 bg-emerald-600 text-white px-3 py-1 m-2 rounded-md text-sm font-semibold">PS</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $type->name }}</h3>
                        <p class="text-gray-600 mb-4 h-24 overflow-hidden">{{ $type->description }}</p>
                        
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Price per Hour</p>
                                <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($type->rental_price_per_hour, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Price per Day</p>
                                <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($type->rental_price_per_day, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('rent.create', $type) }}" class="block w-full text-center btn btn-primary">Rent Now</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-xl text-gray-500">No PlayStation consoles available at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $playstationTypes->links() }}
        </div>
    </div>
</div>
@endsection
