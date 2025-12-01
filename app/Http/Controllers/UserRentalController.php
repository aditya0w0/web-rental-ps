<?php

namespace App\Http\Controllers;

use App\Models\PlaystationType;
use App\Models\PlaystationUnit;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon as Carbon;

class UserRentalController extends Controller
{
    public function index()
    {
        $userId = \Illuminate\Support\Facades\Auth::id();
        $expired = \App\Models\Rental::where('user_id', $userId)
            ->where('status','pending')
            ->whereNull('payment_proof')
            ->where('created_at','<=', \Illuminate\Support\Carbon::now()->subHour())
            ->with(['unit'])
            ->get();
        foreach ($expired as $rental) {
            if ($rental->unit) {
                $rental->unit->update(['status' => 'available']);
            }
            $rental->update(['status' => 'cancelled', 'rejection_reason' => 'Auto-cancelled: payment timeout', 'payment_date' => null]);
        }

        $rentals = \App\Models\Rental::where('user_id', $userId)
            ->latest()
            ->paginate(10);
        return view('rentals.index', compact('rentals'));
    }

    public function show(\App\Models\Rental $rental)
    {
        if ($rental->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }
        if (($rental->status ?? 'pending') === 'pending' && !$rental->payment_proof && $rental->created_at && $rental->created_at->lte(\Illuminate\Support\Carbon::now()->subHour())) {
            if ($rental->unit) {
                $rental->unit->update(['status' => 'available']);
            }
            $rental->update(['status' => 'cancelled', 'rejection_reason' => 'Auto-cancelled: payment timeout', 'payment_date' => null]);
            if ($rental->user) {
                $rental->user->notify(new \App\Notifications\RentalStatusChanged($rental));
            }
        }
        $rental->load(['type','unit']);
        return view('rentals.show', compact('rental'));
    }
    public function create(PlaystationType $type)
    {
        $availableUnits = PlaystationUnit::where('playstation_type_id', $type->id)
            ->where('status', 'available')
            ->count();

        return view('rentals.create', compact('type', 'availableUnits'));
    }

    public function store(Request $request, PlaystationType $type)
    {
        $data = $request->validate([
            'start_time' => 'required|date',
            'duration_type' => 'required|in:hour,day',
            'duration_value' => 'required|integer|min:1',
            'pickup_method' => 'required|in:store_pickup,delivery',
            'delivery_address' => 'nullable|string|required_if:pickup_method,delivery',
            'delivery_city' => 'nullable|string|required_if:pickup_method,delivery',
            'delivery_distance_km' => 'nullable|numeric|min:0',
            'phone_number' => 'required|string',
            'notes' => 'nullable|string',
            'agree_terms' => 'accepted',
        ]);

        $unit = PlaystationUnit::where('playstation_type_id', $type->id)
            ->where('status', 'available')
            ->first();

        if (!$unit) {
            return back()->withInput()->with('error', 'Tidak ada unit tersedia untuk tipe ini.');
        }

        $start = Carbon::parse($data['start_time']);
        $end = (clone $start);
        $amount = (int) $data['duration_value'];
        if ($data['duration_type'] === 'hour') {
            $end->addHours($amount);
            $price = (float) $type->rental_price_per_hour * $amount;
        } else {
            $end->addDays($amount);
            $price = (float) $type->rental_price_per_day * $amount;
        }

        $deliveryFee = 0;
        if ($data['pickup_method'] === 'delivery') {
            $allowed = collect(config('service.allowed_cities'));
            $cityKey = strtolower($data['delivery_city']);
            if (!$allowed->contains($cityKey)) {
                return back()->withInput()->with('error', 'Pengiriman hanya tersedia untuk Pemalang, Batang, dan Pekalongan.');
            }
            $cityMap = [
                'batang' => 15000,
                'pemalang' => 20000,
                'pekalongan' => 10000,
            ];
            $deliveryFee = (float) ($cityMap[$cityKey] ?? 0);
        }

        $rental = Rental::create([
            'user_id' => Auth::id(),
            'playstation_unit_id' => $unit->id,
            'playstation_type_id' => $type->id,
            'start_time' => $start,
            'end_time' => $end,
            'duration_type' => $data['duration_type'],
            'duration_value' => $data['duration_value'],
            'total_price' => $price + $deliveryFee,
            'status' => 'pending',
            'pickup_method' => $data['pickup_method'],
            'delivery_address' => $data['pickup_method'] === 'delivery' ? ($data['delivery_address'] ?? null) : null,
            'delivery_city' => $data['pickup_method'] === 'delivery' ? strtolower($data['delivery_city']) : null,
            'delivery_distance_km' => $data['pickup_method'] === 'delivery' ? ($data['delivery_distance_km'] ?? 0) : null,
            'delivery_fee' => $deliveryFee,
            'phone_number' => $data['phone_number'],
            'notes' => $data['notes'] ?? null,
        ]);

        $unit->update(['status' => 'rented']);

        return redirect()->route('rentals.payment', $rental)->with('success', 'Pengajuan sewa dibuat. Silakan lakukan pembayaran.');
    }

    public function payment(Rental $rental)
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }
        if ($rental->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Rental ini tidak dapat dibayar.');
        }
        if ($rental->payment_proof) {
            return redirect()->route('user.rentals.show', $rental)->with('success', 'Bukti pembayaran sudah diupload. Menunggu konfirmasi admin.');
        }
        return view('rentals.payment', compact('rental'));
    }

    public function processPayment(Request $request, Rental $rental)
    {
        if ($rental->user_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('rental_payment_proofs', 'public');

        $rental->update([
            'payment_proof' => $path,
        ]);

        return redirect()->route('user.rentals.show', $rental)->with('success', 'Bukti pembayaran terkirim. Menunggu konfirmasi admin.');
    }
}
