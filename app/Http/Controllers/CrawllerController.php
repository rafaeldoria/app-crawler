<?php

namespace App\Http\Controllers;

use DOMDocument;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class CrawllerController extends Controller
{
    public function index()
    {
        $data = Http::get('https://pt.wikipedia.org/wiki/ISO_4217');
        $conteudo = $data->body();

        $busca = '<table class="wikitable sortable">';
        $script = str($conteudo)->betweenFirst($busca, "</table>")->value();
        $busca_inicio_tabela = "</th></tr>";
        $busca_final_tabela = "</td></tr></tbody>";
        $tabela = str($script)->betweenFirst($busca_inicio_tabela, $busca_final_tabela)->value();
        $rows = explode('</td></tr>' , $tabela);
        
        $data = [];
        $pattern = '/<td>(.*?)<\/td>/';
        $pattern_fim = '/<td>(.*?)<\/a>/';
        $dom = new DOMDocument;
        // $rows = [$rows[0]];
        // dd($rows);
        foreach ($rows as $key => $row) {

            $values = explode('<td>', $row);
            // dd($values);
            // str_replace('</td>', '', $string);
            // if($key == 174){
            //     dd(explode('<td>', $row));
                
            //     dd($row);
            // }
            // preg_match_all($pattern, $row, $matches);
            // preg_match_all($pattern_fim, $row, $matches_fim);
            // $values = $matches[1];
            

            $moeda = '';
            if(!empty($values[4])){
                $moeda = $values[4];
                // dd('<td>'.$values[4]);
                $dom->loadHTML('<td>'.$values[4]);
                $moedas = $dom->getElementsByTagName('a');
                // dd(is_array($moedas));
                if($moedas->length > 0){
                    $moeda = $moedas[0];
                    $moeda = $moeda->getAttribute('title');
                }
            }

            $imagem = '';
            // $local = '';
            if($key == 174){
                
                // dd($row);
            }
            // dd($values);
            // // $local = end($matches_fim);
            // dd($local);
            if(!empty($values[5])){
                $local = $values[5];
                $dom->loadHTML($local);
                $local = $dom->getElementsByTagName('a');
                $imagem = $dom->getElementsByTagName('img');
                // dd(is_array($moedas));
                if($local->length > 0){
                    $local = $local[0];
                    $local = utf8_decode($local->getAttribute('title'));
                }

                if($imagem->length > 0){
                    $imagem = $imagem[0];
                    $imagem = $imagem->getAttribute('src');
                    if (strpos($imagem, '//') === 0) {
                        $imagem = substr($imagem, 2);
                    }
                }
            }

            // dd(str_replace('</td>', '', (trim(str_replace(["\n", "\r", "\t"], '', $values[1])))));
            
            $data[] = [
                'codigo' => str_replace('</td>', '', (trim(str_replace(["\n", "\r", "\t"], '', $values[1])))),
                'numero' => str_replace('</td>', '', (trim(str_replace(["\n", "\r", "\t"], '', $values[2])))),
                'casas_decimais' => str_replace('</td>', '', (trim(str_replace(["\n", "\r", "\t"], '', $values[3])))),
                'moeda' => utf8_decode($moeda),
                'locais' => [
                    'img' => $imagem,
                    'local' => $local,
                ]
            ];
        }
        
        dd($data);
    }
}