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
        array $currency_locations,
    )
    {
        $this->code = $code;
        $this->number = $number;
        $this->decimal = $decimal;
        $this->currency = $currency;
        $this->currency_locations = $this->transformLocations($currency_locations);
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

    private function transformLocations($currency_locations): array
    {
        $data = [];
        $locations = explode(',',$currency_locations['locations']);

        $icons = $currency_locations['icons'];
        $data = array_map(function ($location, $key) use ($icons) {
            return [
                'location' => trim($location),
                'icon' => isset($icons[$key]) ? $icons[$key] : ''
            ];
        }, $locations, array_keys($locations));

        return $data;

    }
}