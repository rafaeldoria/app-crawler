<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyRequest;
use Exception;
use Illuminate\Http\Response;
use App\Services\CrawlerService;
use App\Services\CurrencyService;

class CurrencyController extends Controller
{
    private CurrencyService $currencyService;
    private CrawlerService $crawlerService;

    public function __construct(
        CurrencyService $currencyService,
        CrawlerService $crawlerService,
    )
    {
        $this->currencyService = $currencyService;
        $this->crawlerService = $crawlerService;
    }

    public function getCurrencyInfo(CurrencyRequest $request)
    {
        try {
            $founds = $this->currencyService->getCurrencyInfo($request->all());
            $crawler = [];

            if(!empty($founds['notFound'])){
                $crawler = $this->crawlerService->getCurrencyInfo($founds['notFound']);
            }

            $currencyDto = [];

            if(!empty($crawler)){
                $currencyDto = $this->currencyService->transformCrawlerToCurrenctyDTO($crawler);
                $this->currencyService->store($currencyDto);
            }
            
            $data = array_merge($founds['found'], $currencyDto);
            
            if(empty($data)){
                return response()->json('Not Found', Response::HTTP_NOT_FOUND);
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }   
    }
}
