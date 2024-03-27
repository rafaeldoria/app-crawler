<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CurrencyService;
use App\Http\Requests\GetCurrencyRequest;
use App\Services\CrawlerService;

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
        // dd($crawler);
        $currencyDto = [];
        if(!empty($crawler)){
            $currencyDto = $this->currencyService->transformCrawlerToCurrenctyDTO($crawler);
            // TODO: PODE SER UMA FILA
            $this->currencyService->store($currencyDto);
        }
        dd($currencyDto);
    }
}
