<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IBaseRepository;

class BaseRepository implements IBaseRepository
{
    public function get(string $field, string $value){}
    public function store(array $data){}
    public function update($model, array $data){}
}
