<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestApiController extends Controller
{
    public function test()
    {
        return response()->json(['message' => 'API działa!', 'status' => 'success'], 200);
    }
}
