<?php

namespace App\Http\Controllers;

use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateApiController extends Controller
{
    public function list(Request $request)
    {
        $city = strtolower($request->query('city'));
        if (!$city) return response()->json([]);
        $rates = ShippingRate::whereRaw('LOWER(city) = ?', [$city])
            ->orderBy('district')
            ->get(['id','district','rate']);
        return response()->json($rates);
    }
}