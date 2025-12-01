@extends('layouts.app')

@section('title', 'Shipping Rates — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Shipping Rates</h1>
        <a href="{{ route('admin.shipping.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded">Add Rate</a>
    </div>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">City</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">District</th>
                    <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rate</th>
                    <th class="px-5 py-3 bg-gray-100"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($rates as $r)
                    <tr class="border-b">
                        <td class="px-5 py-4 text-sm">{{ ucfirst($r->city) }}</td>
                        <td class="px-5 py-4 text-sm">{{ ucfirst($r->district) }}</td>
                        <td class="px-5 py-4 text-sm">Rp {{ number_format($r->rate, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-sm text-right">
                            <a href="{{ route('admin.shipping.edit', $r) }}" class="text-indigo-600 hover:underline mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.shipping.destroy', $r) }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-500">No rates.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $rates->links() }}</div>
</div>
@endsection