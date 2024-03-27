<?php

namespace App\Services;

use Goutte\Client;

class CrawlerService
{
    public function get($substrings)
    {
        $client = new Client();

        $url = env('URL_CRAWLER');

        $crawler = $client->request('GET', $url);
    
        $busca = 'table.wikitable.sortable';
    
        $table = $crawler->filter($busca)->first();

        $rows = $table->filter('tr')->each(function ($row) use ($substrings){
            $check = substr($row->text(), 0, 7);
            $search = explode(' ', $check);
            $intersection = array_intersect($search, $substrings);
            if (!empty($intersection)) {
                $cells = $row->filter('td')->each(function ($cell, $i) {
                    if ($i == 4) {
                        $text = $cell->text();
                        $img = $cell->filter('img')->each(function ($img) {
                            $src = $img->attr('src');
                            if (strpos($src, '//') === 0) {
                                $src = substr($src, 2);
                            }
                            return $src;
                        });
                        return [
                            $text,$img,
                        ];
                    } else {
                        return $cell->text();
                    }
                });
                return $cells;
            }
        });
        return array_filter($rows);
    }
}