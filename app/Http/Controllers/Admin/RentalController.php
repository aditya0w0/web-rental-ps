<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\User;
use App\Models\PlaystationUnit;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['user', 'unit.type'])->latest()->paginate(10);
        return view('admin.rentals.index', compact('rentals'));
    }

    public function create()
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $units = PlaystationUnit::where('status', 'available')->with('type')->get();
        return view('admin.rentals.create', compact('customers', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'playstation_unit_id' => 'required|exists:playstation_units,id',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        $rental = Rental::create($request->all());

        $rental->unit->update(['status' => 'rented']);

        return redirect()->route('admin.rentals.index')
                         ->with('success', 'Rental created successfully.');
    }

    public function edit(Rental $rental)
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $units = PlaystationUnit::with('type')->get();
        return view('admin.rentals.edit', compact('rental', 'customers', 'units'));
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'playstation_unit_id' => 'required|exists:playstation_units,id',
            'rental_start_date' => 'required|date',
            'rental_end_date' => 'required|date|after_or_equal:rental_start_date',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        $originalStatus = $rental->unit->status;

        $rental->update($request->all());

        if ($request->status == 'completed' || $request->status == 'cancelled') {
            $rental->unit->update(['status' => 'available']);
        } else {
            $rental->unit->update(['status' => 'rented']);
        }

        return redirect()->route('admin.rentals.index')
                         ->with('success', 'Rental updated successfully.');
    }

    public function destroy(Rental $rental)
    {
        $rental->unit->update(['status' => 'available']);
        $rental->delete();

        return redirect()->route('admin.rentals.index')
                         ->with('success', 'Rental deleted successfully.');
    }

    public function show(Rental $rental)
    {
        $rental->load(['user','unit.type','type']);
        return view('admin.rentals.show', compact('rental'));
    }

    public function confirmPayment(Rental $rental)
    {
        if ($rental->status !== 'pending' && $rental->status !== 'confirmed') {
            return back()->with('error', 'Rental status cannot be confirmed.');
        }
        $rental->update([
            'status' => 'confirmed',
            'payment_date' => now(),
        ]);
        if ($rental->user) {
            $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
        }
        return back()->with('success', 'Rental payment confirmed.');
    }

    public function rejectPayment(Request $request, Rental $rental)
    {
        if (!in_array($rental->status, ['pending','confirmed'])) {
            return back()->with('error', 'Rental status cannot be rejected.');
        }
        $request->validate(['reason' => 'required|string|min:5']);
        $rental->update([
            'status' => 'cancelled',
            'rejection_reason' => $request->reason,
            'payment_date' => null,
        ]);
        if ($rental->user) {
            $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
        }
        return back()->with('success', 'Rental payment rejected.');
    }

    public function updateFulfillment(Request $request, Rental $rental)
    {
        $request->validate(['status' => 'required|in:none,processing,delivering,ready_for_pickup,completed']);
        $rental->update(['fulfillment_status' => $request->status]);
        if ($rental->user) {
            $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
        }
        return back()->with('success', 'Fulfillment status updated.');
    }

    public function setActive(Rental $rental)
    {
        if ($rental->status !== 'confirmed') {
            return back()->with('error', 'Rental is not confirmed.');
        }
        $rental->update(['status' => 'active']);
        return back()->with('success', 'Rental set to active.');
    }

    public function setCompleted(Rental $rental)
    {
        if (!in_array($rental->status, ['active','confirmed'])) {
            return back()->with('error', 'Rental cannot be completed.');
        }
        $rental->update(['status' => 'completed']);
        return back()->with('success', 'Rental set to completed.');
    }
}
