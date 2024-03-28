<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;
use App\Services\RedisService;

class CurrencyService
{
    private $currencyRepository;
    private $locationRepository;

    public function __construct(
        CurrencyRepository $currencyRepository,
        LocationRepository $locationRepository
    )
    {
        $this->currencyRepository = $currencyRepository;
        $this->locationRepository = $locationRepository;
    }

    public function get($data): array
    {
        $field = 'code';
        if(array_key_exists('number', $data) || array_key_exists('number_lists', $data)){
            $field = 'number';
        }

        $values = array_values($data);
        $strings = '';
        if(array_key_exists('code_list', $data) || array_key_exists('number_lists', $data)){
            $strings = $values[0];
        }else{
            $strings = $values;
        }
        $foundDataBase = [];
        foreach ($strings as $key => $value) {
                $currency = $this->currencyRepository->get($field, $value);
            // $currency = $this->currencyGetCache($field, $value);
            
            if(!is_null($currency)){
                unset($strings[$key]);
                $currency = $this->transformCurrencyToCurrencyDTO($currency);
                array_push($foundDataBase, $currency);
            };
        }
        return [
            'notFound' => $strings,
            'found' =>  $foundDataBase
        ];
    }

    private function currencyGetCache($field, $value)
    {
        $key = 'currency.service.repository.get.'.md5($field).'.'.md5($value);
        $redis = new RedisService();
        if(!$redis->exists($key)){
            $currency = $this->currencyRepository->get($field, $value);
            $redis->set($key, $currency, 30);
            return $currency;
        }
        return $redis->get($key);
    }

    private function transformCurrencyToCurrencyDTO($data): array
    {
        $dto = new CurrencyDTO(
            $data->code, 
            $data->number, 
            $data->decimal, 
            $data->currency
        );

        $dto->transformDBLocations($data->location->toArray());
        return $dto->toArray();
    }

    public function store($data)
    {
        foreach ($data as $key => $value) {
            $currency_locations = $value['currency_locations'];
            unset($value['currency_locations']);
            $currency =$this->currencyRepository->store($value);
            $currency = $currency->toArray();
            if(!empty($currency_locations)){
                foreach ($currency_locations as $key => $value) {
                    $value['currency_id'] = $currency['id'];
                    $this->locationRepository->store($value);
                }
            }
        }
    }

    public function transformCrawlerToCurrenctyDTO($data): array
    {
        return array_map(function ($value) {
            $dto = new CurrencyDTO(
                $value[0],
                $value[1],
                $value[2],
                $value[3],
            );
            $currency_locations = [
                'locations' => html_entity_decode(str_replace("&nbsp;", "", htmlentities($value[4][0]))) ?? null,
                'icons' => $value[4][1] ?? null
            ];
            $dto->currency_locations($this->transformLocations($currency_locations));
            return $dto->toArray();
        }, $data);
    }

    private function transformLocations($currency_locations): array
    {
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