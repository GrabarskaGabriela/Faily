<?php

namespace App\Services\Interfaces;

interface ServiceInterface
{
    public function getAll(array $columns = ['*']);
    public function findById($id, array $columns = ['*']);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate($perPage = 15, array $columns = ['*']);
}
