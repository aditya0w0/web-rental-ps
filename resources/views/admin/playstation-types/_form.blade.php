@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">There were some problems with your input.</span>
        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name', $type->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
        <div class="mt-2">
            <label class="inline-flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $type->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Active</span>
            </label>
        </div>
    </div>
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>{{ old('description', $type->description) }}</textarea>
    </div>
    <div>
        <label for="rental_price_per_hour" class="block text-sm font-medium text-gray-700">Rental Price per Hour</label>
        <input type="number" name="rental_price_per_hour" id="rental_price_per_hour" value="{{ old('rental_price_per_hour', $type->rental_price_per_hour) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required min="0" step="1000">
    </div>
    <div>
        <label for="rental_price_per_day" class="block text-sm font-medium text-gray-700">Rental Price per Day</label>
        <input type="number" name="rental_price_per_day" id="rental_price_per_day" value="{{ old('rental_price_per_day', $type->rental_price_per_day) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required min="0" step="1000">
    </div>
    <div class="md:col-span-2">
        <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
        <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
        @if($type->image)
            <div class="mt-4">
                    <img src="{{ $type->image_url }}" alt="{{ $type->name }}" class="h-32 w-auto object-cover rounded-md">
            </div>
        @endif
    </div>
</div>
