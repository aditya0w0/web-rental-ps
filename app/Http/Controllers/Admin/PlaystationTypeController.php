<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaystationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaystationTypeController extends Controller
{
    public function index()
    {
        $types = PlaystationType::latest()->paginate(10);
        return view('admin.playstation-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.playstation-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'rental_price_per_hour' => 'required|numeric|min:0',
            'rental_price_per_day' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('playstation-types', 'public');
        }

        PlaystationType::create($data);

        return redirect()->route('admin.playstation-types.index')->with('success', 'PlayStation type created successfully.');
    }

    public function edit(PlaystationType $playstationType)
    {
        return view('admin.playstation-types.edit', compact('playstationType'));
    }

    public function update(Request $request, PlaystationType $playstationType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'rental_price_per_hour' => 'required|numeric|min:0',
            'rental_price_per_day' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('image');
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($playstationType->image) {
                Storage::disk('public')->delete($playstationType->image);
            }
            $data['image'] = $request->file('image')->store('playstation-types', 'public');
        }

        $playstationType->update($data);

        return redirect()->route('admin.playstation-types.index')->with('success', 'PlayStation type updated successfully.');
    }

    public function destroy(PlaystationType $playstationType)
    {
        if ($playstationType->rentals()->exists() || $playstationType->units()->exists()) {
            $playstationType->update(['is_active' => false]);

            return redirect()->route('admin.playstation-types.index')
                             ->with('success', 'PlayStation type has history or units, so it was deactivated instead of deleted.');
        }

        if ($playstationType->image) {
            Storage::disk('public')->delete($playstationType->image);
        }
        
        $playstationType->delete();

        return redirect()->route('admin.playstation-types.index')->with('success', 'PlayStation type deleted successfully.');
    }
}
