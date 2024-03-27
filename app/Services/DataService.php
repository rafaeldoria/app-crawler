<?php

namespace App\Services;

use App\DTO\CurrencyDTO;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;

class DataService 
{
    public function show($data)
    {
        $values = array_values($data);
        if(array_key_exists('code_list', $data) || array_key_exists('number_lists', $data)){
            $strings = $values[0];
        }else{
            $strings = $values;
        }
        // dd($strings);
        
        $currencyService = new CurrencyService(new CurrencyRepository());
        $getCurrencies = $currencyService->get($strings);
        $foundDataBase = $getCurrencies['found'];
        $strings = $getCurrencies['notfound'];
       dd($foundDataBase, $strings);
        $crawler = [];
        if(!empty($strings)){
            $crawler = (new CrawlerService)->show($strings);
        }
        
        $response = [];
        if(!empty($crawler)){
            $foundCrawler = $this->transformCrawlerToCurrenctyDTO($crawler);
            foreach ($foundCrawler as $key => $value) {
                $currency_locations = $value['currency_locations'];
                unset($value['currency_locations']);
                $currency = (new CurrencyRepository)->store($value);
                $currency = $currency->toArray();
                if(!empty($currency_locations)){
                    foreach ($currency_locations as $key => $value) {
                        $value['currency_id'] = $currency['id'];
                        (new LocationRepository)->store($value);
                    }
                }
                
            }
        }
        // dd($foundDataBase, $crawler);
        return $response;
    }

    private function transformCrawlerToCurrenctyDTO($data)
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
            $dto->transformLocations($currency_locations);
            return $dto->toArray();
        }, $data);
    }

}