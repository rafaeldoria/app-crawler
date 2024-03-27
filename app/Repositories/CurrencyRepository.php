<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Repositories\Interfaces\ICurrencyRepository;

class CurrencyRepository extends BaseRepository
{
    public function get(string $field, string $value)
    {
        $currencies = new Currency();
        $currencies = $currencies->where($field, $value)
            ->first();

        return $currencies;
    }

    public function store($data)
    {
        $currency = new Currency();
        return $currency->create($data);
    }
}