<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class PhanterController extends Controller
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

        $dom = new DOMDocument;
        $dom->loadHTML($tabela);

        $linhas = $dom->getElementsByTagName('tr');
        foreach ($linhas as $linha) {
            $linhaDados = [];
            $celulas = $linha->getElementsByTagName('td');
            
            foreach ($celulas as $key => $celula) {
                // var_dump($key);
                // if($key == 4){
                //     dd(self::decodeUnicodeEscapeSequence($celula->nodeValue));
                // }
                $linhaDados[] = mb_convert_encoding($celula->nodeValue, 'UTF-8', 'HTML-ENTITIES');
            }
            
            $tabelaDados[] = $linhaDados;
            // dd($tabelaDados);
        }
        
dd($tabelaDados);
        // $rows = explode('</td></tr>' , $tabela);
        
        // $data = [];
        // $pattern = '/<td>(.*?)<\/td>/';
        // $pattern_fim = '/<td>(.*?)<\/a>/';
        // foreach ($rows as $key => $row) {
        //     preg_match_all($pattern, $row, $matches);
        //     preg_match_all($pattern_fim, $row, $matches_fim);
        //     $values = $matches[1];
        //     $local = end($matches_fim[1]);
        //     $data[] = [
        //         'codigo' => $values[0],
        //         'numero' => $values[1],
        //         'casas_decimais' => $values[2],
        //         'moeda' => $values[3],
        //         'locais' => $local
        //     ];
        // }
        
        // dd($data);
    }

    public static function decodeUnicodeEscapeSequence($str) {
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }
       
}