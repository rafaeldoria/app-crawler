<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;
use App\Repositories\Interfaces\ICurrencyRepository;

class CurrencyService
{
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }


    public function get($strings): array
    {
        $foundDataBase = [];
        foreach ($strings as $key => $value) {
            $field = 'code';
            if(is_numeric($value)){
                $field = 'number';
            }
            $currency = $this->currencyRepository->get($field, $value);
            
            if(!is_null($currency)){
                unset($strings[$key]);
                $currency = $this->transformCurrencyToCurrenctyDTO($currency);
                array_push($foundDataBase, $currency);
            };
        }
        return [
            'notfound' => $strings,
            'found' =>  $foundDataBase
        ];
    }

    private function transformCurrencyToCurrenctyDTO($data): CurrencyDTO
    {
        $dto = new CurrencyDTO(
            $data->code, 
            $data->number, 
            $data->decimal, 
            $data->currency
        );
        // dd($data->location->toArray());
        $dto->transformDBLocations($data->location->toArray());
        return $dto;
    }
}