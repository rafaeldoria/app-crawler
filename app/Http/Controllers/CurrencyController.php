<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\CrawlerService;
use App\Services\CurrencyService;
use App\Http\Requests\GetCurrencyRequest;

class CurrencyController extends Controller
{
    protected $currencyService;
    protected $crawlerService;

    public function __construct(
        CurrencyService $currencyService,
        CrawlerService $crawlerService
    )
    {
        $this->currencyService = $currencyService;
        $this->crawlerService = $crawlerService;
    }

    public function get(GetCurrencyRequest $request)
    {
        $validated = $request->validated();
        $founds = $this->currencyService->get($validated);
        
        $crawler = [];
        if(!empty($founds['notFound'])){
            $crawler = $this->crawlerService->get($founds['notFound']);
        }

        $currencyDto = [];
        if(!empty($crawler)){
            $currencyDto = $this->currencyService->transformCrawlerToCurrenctyDTO($crawler);
            dd($currencyDto);
            // TODO: PODE SER UMA FILA
            $this->currencyService->store($currencyDto);
        }
        $data = array_merge($founds['found'], $currencyDto);
        return response()->json($data, Response::HTTP_OK);
    }
}
