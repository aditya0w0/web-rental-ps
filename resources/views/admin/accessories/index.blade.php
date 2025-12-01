@extends('layouts.app')

@section('title', 'Accessories - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Accessories</h1>
        <a href="{{ route('admin.accessories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Add New Accessory
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full bg-white">
            <thead class="bg-green-50 text-emerald-700 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Image</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Category</th>
                    <th class="py-3 px-6 text-right">Price</th>
                    <th class="py-3 px-6 text-center">Stock</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($accessories as $accessory)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">
                            @if($accessory->image)
                                <img src="{{ asset('storage/' . $accessory->image) }}" alt="{{ $accessory->name }}" class="h-12 w-12 object-cover rounded-md">
                            @else
                                <div class="h-12 w-12 bg-gray-200 rounded-md flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-left whitespace-nowrap font-medium">{{ $accessory->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $accessory->category }}</td>
                        <td class="py-3 px-6 text-right">Rp {{ number_format($accessory->price, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-center">{{ $accessory->stock }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $accessory->is_active ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $accessory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center">
                                <a href="{{ route('admin.accessories.edit', $accessory) }}" class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center mr-2 hover:bg-blue-600">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.accessories.destroy', $accessory) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this accessory?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">No accessories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $accessories->links() }}
    </div>
</div>
@endsection