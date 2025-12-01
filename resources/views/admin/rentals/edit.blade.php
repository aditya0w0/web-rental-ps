@extends('layouts.app')

@section('title', 'Edit Rental - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Rental</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.rentals.update', $rental) }}" method="POST">
            @csrf
            @method('PUT')
            
            @include('admin.rentals._form', ['rental' => $rental])

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.rentals.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Update Rental</button>
            </div>
        </form>
    </div>
</div>
@endsection