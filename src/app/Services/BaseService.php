<?php

namespace App\Services;

use App\Services\Interfaces\CacheServiceInterface;
use App\Services\Interfaces\ServiceInterface;
use App\Repositories\Interfaces\RepositoryInterface;

abstract class BaseService implements ServiceInterface
{
    protected $repository;
    protected $cacheService;
    protected $cacheTags = [];
    protected $cachePrefix = 'service';
    protected $cacheTimes = [
        'all' => 60 * 15,        //15 min
        'find' => 60 * 30,      //30 min
        'paginate' => 60 * 5,   //5 min
    ];

    public function __construct(
        RepositoryInterface $repository,
        ?CacheServiceInterface $cacheService = null
    ) {
        $this->repository = $repository;
        $this->cacheService = $cacheService;

        if ($this->cachePrefix === 'service') {
            $className = basename(str_replace('\\', '/', get_class($this)));
            $this->cachePrefix = strtolower(str_replace('Service', '', $className));
        }
    }

    protected function useCache(): bool
    {
        return $this->cacheService !== null && config('cache.enabled', true);
    }

    public function getAll(array $columns = ['*'])
    {
        if (!$this->useCache()) {
            return $this->repository->all($columns);
        }

        return $this->cacheService->rememberWithTags(
            $this->cacheTags,
            "{$this->cachePrefix}.all",
            function () use ($columns) {
                return $this->repository->all($columns);
            },
            $this->cacheTimes['all']
        );
    }

    public function findById($id, array $columns = ['*'])
    {
        if (!$this->useCache()) {
            return $this->repository->find($id, $columns);
        }

        return $this->cacheService->remember(
            "{$this->cachePrefix}.{$id}",
            function () use ($id, $columns) {
                return $this->repository->find($id, $columns);
            },
            $this->cacheTimes['find']
        );
    }

    public function store(array $data)
    {
        $result = $this->repository->create($data);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.all");
            if (!empty($this->cacheTags)) {
                $this->cacheService->flushTags($this->cacheTags);
            }
        }

        return $result;
    }

    public function update($id, array $data)
    {
        $result = $this->repository->update($data, $id);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$id}");
            $this->cacheService->forget("{$this->cachePrefix}.all");
            if (!empty($this->cacheTags)) {
                $this->cacheService->flushTags($this->cacheTags);
            }
        }

        return $result;
    }

    public function delete($id)
    {
        $result = $this->repository->delete($id);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$id}");
            $this->cacheService->forget("{$this->cachePrefix}.all");
            if (!empty($this->cacheTags)) {
                $this->cacheService->flushTags($this->cacheTags);
            }
        }

        return $result;
    }

    public function paginate($perPage = 15, array $columns = ['*'])
    {
        if (!$this->useCache()) {
            return $this->repository->paginate($perPage, $columns);
        }

        $page = request()->get('page', 1);

        return $this->cacheService->remember(
            "{$this->cachePrefix}.paginate.{$perPage}.{$page}",
            function () use ($perPage, $columns) {
                return $this->repository->paginate($perPage, $columns);
            },
            $this->cacheTimes['paginate']
        );
    }
}
