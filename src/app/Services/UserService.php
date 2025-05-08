<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class UserService extends BaseService implements UserServiceInterface
{
    protected $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function updateProfile(array $data, $userId, ?UploadedFile $avatar = null)
    {
        $user = $this->repository->find($userId);

        if ($avatar) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $photoPath = $avatar->store('profile-photos', 'public');
            $data['photo_path'] = $photoPath;
            $data['photo_updated_at'] = now();
        }

        if (isset($data['preferred_language'])) {
            $data['language'] = $data['preferred_language'];
            unset($data['preferred_language']);
        }

        if (isset($data['avatar'])) {
            unset($data['avatar']);
        }

        if (isset($data['photo'])) {
            unset($data['photo']);
        }

        return $this->repository->updateProfile($userId, $data);
    }

    public function updatePassword(array $data, $userId)
    {
        $user = $this->repository->find($userId);

        if (!Hash::check($data['current_password'], $user->password)) {
            throw new \Exception('Current password is incorrect.');
        }

        $hashedPassword = Hash::make($data['password']);

        return $this->repository->updatePassword($userId, $hashedPassword);
    }

    public function updatePhoto(UploadedFile $photo, $userId)
    {
        $user = $this->repository->find($userId);

        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $photoPath = $photo->store('profile-photos', 'public');

        return $this->repository->updatePhoto($userId, $photoPath);
    }

    public function toggle2FA($enabled, $userId)
    {
        return $this->repository->update2FASettings($userId, $enabled);
    }

    public function deleteAccount($password, $userId)
    {
        $user = $this->repository->find($userId);

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('Password is incorrect.');
        }
        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        return $this->repository->delete($userId);
    }

    public function banUser($userId)
    {
        $user = $this->repository->find($userId);

        if (!$user) {
            throw new \Exception('User not found.');
        }

        if ($user->role === 'admin') {
            throw new \Exception('You can\'t ban other admin user.');
        }

        return $this->repository->update($userId, 'banned');
    }

    public function countBannedUsers()
    {
        return $this->repository->countByStatus('banned');
    }

    public function unbanUser($userId)
    {
        $user = $this->repository->updateStatus($userId, 'banned');
        $this->repository->resetReportCount($userId);
        return $user;
    }

    public function makeAdmin($userId)
    {
        return $this->repository->updateStatus($userId, 'admin');
    }

    public function removeAdmin($userId, $currentUserId)
    {
        if ($currentUserId == $userId) {
            throw new \Exception('You can\'t take away admin privileges yourself.');
        }

        return $this->repository->updateStatus($userId, 'admin');
    }

    public function all()
    {
        return $this->getAll();
    }

    public function paginate($perPage = 15, array $columns = ['*'])
    {
        return User::paginate($perPage, $columns);
    }
}
