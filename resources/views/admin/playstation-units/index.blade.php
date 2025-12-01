@extends('layouts.app')

@section('title', 'PlayStation Units — Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">PlayStation Units</h1>
        <a href="{{ route('admin.playstation-units.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded">Add Unit</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit Code</th>
                    <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Serial</th>
                    <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $u)
                    <tr class="border-b hover:bg-gray-50/60">
                        <td class="px-5 py-4 text-sm font-medium text-gray-800">{{ $u->unit_code }}</td>
                        <td class="px-5 py-4 text-sm text-gray-700">{{ $u->type?->name }}</td>
                        <td class="px-5 py-4 text-sm text-gray-700 tracking-wide">{{ $u->serial_number }}</td>
                        <td class="px-5 py-4 text-sm">
                            @php $st = $u->status; @endphp
                            @if($st==='available')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Available</span>
                            @elseif($st==='rented')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-pink-50 text-pink-700">Rented</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">Maintenance</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-sm">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.playstation-units.edit', $u) }}" class="inline-flex items-center px-3 py-1.5 rounded-md bg-purple-600 text-white hover:bg-purple-700">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="mr-1"><path d="M12 20h9" stroke-width="2"/><path d="M16.5 3.5l4 4L7 21H3v-4L16.5 3.5z" stroke-width="2"/></svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.playstation-units.destroy', $u) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Delete this unit?')">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="mr-1"><path d="M3 6h18" stroke-width="2"/><path d="M8 6V4h8v2" stroke-width="2"/><path d="M19 6l-1 14H6L5 6" stroke-width="2"/></svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada unit.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $units->links() }}
    </div>
</div>
@endsection

