<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function updateProfile($userId, array $data);

    public function updatePassword($userId, $password);

    public function updatePhoto($userId, $photoPath);

    public function update2FASettings($userId, $enabled);
}
