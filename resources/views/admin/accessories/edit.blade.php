@extends('layouts.app')

@section('title', 'Edit Accessory - Admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Accessory</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.accessories.update', $accessory) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            @include('admin.accessories._form', ['accessory' => $accessory])

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.accessories.index') }}" class="btn btn-light mr-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Accessory</button>
            </div>
        </form>
    </div>
</div>
@endsection