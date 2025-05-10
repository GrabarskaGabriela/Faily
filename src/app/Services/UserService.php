<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\CacheServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class UserService extends BaseService implements UserServiceInterface
{
    protected $repository;
    protected $cacheService;

    public function __construct(
        UserRepositoryInterface $repository,
        ?CacheServiceInterface $cacheService = null
    )
    {
        parent::__construct($repository, $cacheService);

        $this->cacheTags = ['users'];
        $this->cachePrefix = 'user';
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

        $result = $this->repository->updateProfile($userId, $data);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->flushTags(['users']);
        }

        return $result;
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
        $result = $this->repository->updatePhoto($userId, $photoPath);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->flushTags(['users']);
        }

        return $result;
    }

    public function toggle2FA($enabled, $userId)
    {
        $result = $this->repository->update2FASettings($userId, $enabled);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
        }

        return $result;
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

        $result = $this->repository->delete($userId);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->flushTags(['users']);
        }

        return $result;
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

        $result = $this->repository->update($userId, 'banned');

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->forget("{$this->cachePrefix}.banned.count");
            $this->cacheService->flushTags(['users']);
        }

        return $result;
    }



    public function countBannedUsers()
    {
        if (!$this->useCache()) {
            return $this->repository->countByStatus('banned');
        }

        return $this->cacheService->remember(
            "{$this->cachePrefix}.banned.count",
            function () {
                return $this->repository->countByStatus('banned');
            },
            60 * 60 //1h
        );
    }

    public function unbanUser($userId)
    {
        $result = $this->repository->updateStatus($userId, 'banned');
        $this->repository->resetReportCount($userId);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->forget("{$this->cachePrefix}.banned.count");
            $this->cacheService->flushTags(['users']);
        }

        return $result;
    }

    public function makeAdmin($userId)
    {
        $result = $this->repository->updateStatus($userId, 'admin');

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->flushTags(['users',  'admins']);
        }

        return $result;
    }

    public function removeAdmin($userId, $currentUserId)
    {
        if ($currentUserId == $userId) {
            throw new \Exception('You can\'t take away admin privileges yourself.');
        }

        $result = $this->repository->updateStatus($userId, 'user');
        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$userId}");
            $this->cacheService->flushTags(['users', 'admins']);
        }

        return $result;
    }

    public function all()
    {
        return $this->getAll();
    }

    public function paginate($perPage = 15, array $columns = ['*'])
    {
        if (!$this->useCache()) {
            return User::paginate($perPage, $columns);
        }

        $page = request()->get('page', 1);

        return $this->cacheService->remember(
            "{$this->cachePrefix}.paginate.{$perPage}.{$page}",
            function () use ($perPage, $columns) {
                return User::paginate($perPage, $columns);
            },
            $this->cacheTimes['paginate']
        );
    }
}
