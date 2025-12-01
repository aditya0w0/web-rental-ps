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
        <label for="playstation_type_id" class="block text-sm font-medium text-gray-700">PlayStation Type</label>
        <select name="playstation_type_id" id="playstation_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Select a Type</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}" {{ old('playstation_type_id', $unit->playstation_type_id) == $type->id ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="unit_code" class="block text-sm font-medium text-gray-700">Unit Code (optional)</label>
        <input type="text" name="unit_code" id="unit_code" value="{{ old('unit_code', $unit->unit_code) }}" placeholder="Auto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>
    <div>
        <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $unit->serial_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="available" {{ old('status', $unit->status) === 'available' ? 'selected' : '' }}>Available</option>
            <option value="rented" {{ old('status', $unit->status) === 'rented' ? 'selected' : '' }}>Rented</option>
            <option value="maintenance" {{ old('status', $unit->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
    </div>
    <div class="md:col-span-2">
        <label for="condition_notes" class="block text-sm font-medium text-gray-700">Condition Notes</label>
        <textarea name="condition_notes" id="condition_notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('condition_notes', $unit->condition_notes) }}</textarea>
    </div>
</div>