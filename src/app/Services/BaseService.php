<?php

namespace App\Services;

use App\Services\Interfaces\ServiceInterface;
use App\Repositories\Interfaces\RepositoryInterface;
abstract class BaseService implements ServiceInterface
{
    protected $repository;

    public function getAll(array $columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    public function findById($id, array $columns = ['*'])
    {
        return $this->repository->find($id, $columns);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function paginate($perPage = 15, array $columns = ['*'])
    {
        return $this->repository->paginate($perPage, $columns);
    }
}
