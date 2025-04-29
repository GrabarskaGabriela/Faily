<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface UserServiceInterface extends ServiceInterface
{
    public function updateProfile(array $data, $userId, ?UploadedFile $avatar = null);
    public function updatePassword(array $data, $userId);
    public function updatePhoto(UploadedFile $photo, $userId);
    public function toggle2FA($enabled, $userId);
    public function deleteAccount($password, $userId);
}
