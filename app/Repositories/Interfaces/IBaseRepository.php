<?php

namespace App\Repositories\Interfaces;

interface IBaseRepository
{
    public function get(string $field, string $value);
    public function store(array $data);
    public function update($model, array $data);
}
