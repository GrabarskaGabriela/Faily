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

        // Laravel HTTP client już obsługuje CORS, bo zapytanie jest wykonywane z serwera
        $response = Http::get('https://nominatim.openstreetmap.org/search', [
            'format' => 'json',
            'q' => $query,
            'limit' => $limit,
            // Dodanie wymaganego User-Agent zgodnie z polityką Nominatim
            'headers' => [
                'User-Agent' => 'YourAppName/1.0'
            ]
        ]);

        return $response->json();
    }

    public function reverse(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        // Laravel HTTP client
        $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lon,
            // Dodanie wymaganego User-Agent zgodnie z polityką Nominatim
            'headers' => [
                'User-Agent' => 'YourAppName/1.0'
            ]
        ]);

        return $response->json();
    }
}
