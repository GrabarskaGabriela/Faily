<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestCacheController extends Controller
{
    public function testCache()
    {
        Cache::put('test-key', 'Hello Redis Cache!', 60 * 10); // 10 minut

        $value = Cache::get('test-key');

        $driver = Cache::getDefaultDriver();

        return response()->json([
            'cache_value' => $value,
            'cache_driver' => $driver,
            'timestamp' => now()
        ]);
    }
}
