@extends('layouts.app')

@section('title', 'Add Shipping Rate — Admin')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Add Shipping Rate</h1>
    <form method="POST" action="{{ route('admin.shipping.store') }}" class="space-y-4 bg-white shadow rounded-lg p-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">City</label>
            <input type="text" name="city" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">District</label>
            <input type="text" name="district" class="mt-1 w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Rate</label>
            <input type="number" name="rate" class="mt-1 w-full border rounded px-3 py-2" required min="0" step="1000">
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.shipping.index') }}" class="px-4 py-2 bg-gray-200 rounded">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Save</button>
        </div>
    </form>
</div>
@endsection