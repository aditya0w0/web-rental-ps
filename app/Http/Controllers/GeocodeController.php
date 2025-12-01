<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeocodeController extends Controller
{
    public function distance(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
        ]);
        $query = $request->address . ', ' . $request->city . ', Indonesia';

        $coords = $this->geocode($query);
        if (!$coords) {
            return response()->json(['error' => 'Geocode failed'], 422);
        }

        $shopLat = -6.8937849; $shopLon = 109.6723764;
        $lat = $coords['lat']; $lon = $coords['lon'];
        $km = $this->haversine($shopLat, $shopLon, $lat, $lon);
        return response()->json(['km' => ceil($km)]);
    }

    private function geocode(string $query): ?array
    {
        $url = 'https://nominatim.openstreetmap.org/search?format=json&countrycodes=id&q=' . urlencode($query);
        $ctx = stream_context_create(['http' => ['header' => "User-Agent: PlayHub/1.0\r\n"]]);
        $json = @file_get_contents($url, false, $ctx);
        $data = $json ? json_decode($json, true) : [];
        if (!$data || !count($data)) {
            $url = 'https://geocode.maps.co/search?q=' . urlencode($query);
            $json = @file_get_contents($url, false, $ctx);
            $data = $json ? json_decode($json, true) : [];
        }
        if (!$data || !count($data)) return null;
        $item = $data[0];
        $lat = isset($item['lat']) ? (float)$item['lat'] : (float)($item['lat'] ?? 0);
        $lon = isset($item['lon']) ? (float)$item['lon'] : (float)($item['lon'] ?? 0);
        if (!$lat && !$lon) return null;
        return ['lat' => $lat, 'lon' => $lon];
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $R * $c;
    }
}