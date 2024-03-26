<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
class GoutteController extends Controller
{
    public function index()
    {
        $client = new Client();

        $url = 'https://pt.wikipedia.org/wiki/ISO_4217';
    
        $crawler = $client->request('GET', $url);
    
        $busca = 'table.wikitable.sortable';
    
        $table = $crawler->filter($busca)->first();

        $substrings = ["GBP", "GEL", "HKD"];
        
        
        $rows = $table->filter('tr')->each(function ($row, $i) use ($substrings){
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
        $rows = array_filter($rows);

        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'codigo' => $row[0] ?? null,
                'numero' => $row[1] ?? null,
                'casas_decimais' => $row[2] ?? null,
                'moeda' => $row[3] ?? null,
                'locais' => [
                    'locations' => html_entity_decode(str_replace("&nbsp;", "", htmlentities($row[4][0]))) ?? null,
                    'icons' => $row[4][1] ?? null
                ],
            ];
        }
    
        return response()->json($data, Response::HTTP_OK);
    }
}