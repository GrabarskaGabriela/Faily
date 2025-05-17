<?php

namespace App\Services\Interfaces;

use Illuminate\Http\Request;

interface PhotoServiceInterface extends ServiceInterface
{
    public function storeEventPhotos(Request $request, $userId);
    public function deletePhoto($photoId, $userId);
}
