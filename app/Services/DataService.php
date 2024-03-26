<?php

namespace App\Services;

use App\DTO\CurrencyDTO;

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

        $crawler = (new CrawlerService)->show($strings);

        $response = [];
        if(!empty($crawler)){
            $response = $this->transformToCurrenctyDTO($crawler);
        }
        return $response;
    }

    private function transformToCurrenctyDTO($data)
    {
        return array_map(function ($value) {
            $dto = new CurrencyDTO(
                $value[0],
                $value[1],
                $value[2],
                $value[3],
                [
                    'locations' => html_entity_decode(str_replace("&nbsp;", "", htmlentities($value[4][0]))) ?? null,
                    'icons' => $value[4][1] ?? null
                ]
            );
            return $dto->toArray();
        }, $data);
    }
}