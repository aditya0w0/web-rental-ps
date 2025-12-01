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
        <label for="user_id" class="block text-sm font-medium text-gray-700">Customer</label>
        <select name="user_id" id="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Select a Customer</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('user_id', $rental->user_id) == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="playstation_unit_id" class="block text-sm font-medium text-gray-700">PlayStation Unit</label>
        <select name="playstation_unit_id" id="playstation_unit_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="">Select a Unit</option>
            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ old('playstation_unit_id', $rental->playstation_unit_id) == $unit->id ? 'selected' : '' }}>
                    {{ $unit->playstationType->name }} - {{ $unit->unit_code }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="rental_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" name="rental_start_date" id="rental_start_date" value="{{ old('rental_start_date', $rental->rental_start_date ? $rental->rental_start_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="rental_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" name="rental_end_date" id="rental_end_date" value="{{ old('rental_end_date', $rental->rental_end_date ? $rental->rental_end_date->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
    </div>
    <div>
        <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
        <input type="number" name="total_price" id="total_price" value="{{ old('total_price', $rental->total_price) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required min="0" step="1000">
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
            <option value="pending" {{ old('status', $rental->status) === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="active" {{ old('status', $rental->status) === 'active' ? 'selected' : '' }}>Active</option>
            <option value="completed" {{ old('status', $rental->status) === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ old('status', $rental->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
</div>