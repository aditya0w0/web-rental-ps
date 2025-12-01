<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaystationUnit;
use App\Models\PlaystationType;
use Illuminate\Http\Request;

class PlaystationUnitController extends Controller
{
    public function index()
    {
        $units = PlaystationUnit::with('type')->latest()->paginate(10);
        return view('admin.playstation-units.index', compact('units'));
    }

    public function create()
    {
        $types = PlaystationType::where('is_active', true)->orderBy('name')->get();
        return view('admin.playstation-units.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'playstation_type_id' => 'required|exists:playstation_types,id',
            'unit_code' => 'nullable|string|max:255|unique:playstation_units,unit_code',
            'serial_number' => 'required|string|max:255|unique:playstation_units,serial_number',
            'status' => 'required|in:available,rented,maintenance',
            'condition_notes' => 'nullable|string',
        ]);

        if (empty($validated['unit_code'])) {
            $type = PlaystationType::find($validated['playstation_type_id']);
            $prefix = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $type->name));
            $n = 1;
            do {
                $code = $prefix . '-' . str_pad($n, 2, '0', STR_PAD_LEFT);
                $exists = PlaystationUnit::where('unit_code', $code)->exists();
                $n++;
            } while ($exists);
            $validated['unit_code'] = $code;
        }

        PlaystationUnit::create($validated);

        return redirect()->route('admin.playstation-units.index')
                         ->with('success', 'PlayStation unit created successfully.');
    }

    public function edit(PlaystationUnit $playstationUnit)
    {
        $types = PlaystationType::where('is_active', true)->orderBy('name')->get();
        return view('admin.playstation-units.edit', compact('playstationUnit', 'types'));
    }

    public function update(Request $request, PlaystationUnit $playstationUnit)
    {
        $validated = $request->validate([
            'playstation_type_id' => 'required|exists:playstation_types,id',
            'unit_code' => 'required|string|max:255|unique:playstation_units,unit_code,' . $playstationUnit->id,
            'serial_number' => 'required|string|max:255|unique:playstation_units,serial_number,' . $playstationUnit->id,
            'status' => 'required|in:available,rented,maintenance',
            'condition_notes' => 'nullable|string',
        ]);

        $playstationUnit->update($validated);

        return redirect()->route('admin.playstation-units.index')
                         ->with('success', 'PlayStation unit updated successfully.');
    }

    public function destroy(PlaystationUnit $playstationUnit)
    {
        $playstationUnit->delete();

        return redirect()->route('admin.playstation-units.index')
                         ->with('success', 'PlayStation unit deleted successfully.');
    }
}
