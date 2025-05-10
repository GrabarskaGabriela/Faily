<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function updateProfile($userId, array $data);

    public function updatePassword($userId, $password);

    public function updatePhoto($userId, $photoPath);

    public function update2FASettings($userId, $enabled);

    public function updateRole($userId, $role);
    public function updateStatus($userId, $status);
    public function incrementReportCount($userId);
    public function resetReportCount($userId);
    public function countByStatus($status);
    public function getAdmins();
}
