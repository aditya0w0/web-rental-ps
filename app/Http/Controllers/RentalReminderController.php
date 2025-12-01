<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Support\Carbon;

class RentalReminderController extends Controller
{
    public function send()
    {
        $now = Carbon::now();
        $threshold = $now->copy()->addHours(2);
        $rentals = Rental::whereIn('status', ['confirmed','active'])
            ->whereNull('ending_soon_notified_at')
            ->whereNotNull('end_time')
            ->whereBetween('end_time', [$now, $threshold])
            ->with('user')
            ->get();

        foreach ($rentals as $rental) {
            if ($rental->user) {
                $rental->user->notify(new \App\Notifications\RentalEndingSoon($rental));
            }
            $rental->forceFill(['ending_soon_notified_at' => Carbon::now()])->save();
        }

        return response()->json(['sent' => $rentals->count()]);
    }
}