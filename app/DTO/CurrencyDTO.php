<?php

namespace App\DTO;

class CurrencyDTO
{
    protected string $code;
    protected int $number;
    protected int $decimal;
    protected string $currency;
    protected array $currency_locations;

    public function __construct(
        string $code,
        int $number,
        int $decimal,
        string $currency,
    )
    {
        $this->code = $code;
        $this->number = $number;
        $this->decimal = $decimal;
        $this->currency = $currency;
    }

    public function currency_locations($currency_locations)
    {
        $this->currency_locations = $currency_locations;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'number' => $this->number,
            'decimal' => $this->decimal,
            'currency' => $this->currency,
            'currency_locations' => $this->currency_locations,
        ];
    }

    public function transformDBLocations($locations)
    {
        foreach ($locations as $key => $value) {
            $this->currency_locations[] = [
                'location' => $value['location'],
                'icon' => $value['icon']
            ];
        }
    }
}