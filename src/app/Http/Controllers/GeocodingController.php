<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodingController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('q');
        $limit = $request->query('limit', 5);

        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'format' => 'json',
            'q' => $query,
            'limit' => $limit,
            'headers' => [
                'User-Agent' => 'Faily/1.0'
            ]
        ]);

        return $response->json();
    }

    public function reverse(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lon,
            'headers' => [
                'User-Agent' => 'Faily/1.0'
            ]
        ]);

        return $response->json();
    }
}
