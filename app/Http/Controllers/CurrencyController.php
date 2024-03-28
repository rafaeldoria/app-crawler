<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\StoreCurrencyJob;
use App\Services\CrawlerService;
use App\Services\CurrencyService;
use App\Services\CurrencyValidator;

class CurrencyController extends Controller
{
    protected $currencyService;
    protected $crawlerService;
    protected $currencyValidator;

    public function __construct(
        CurrencyService $currencyService,
        CrawlerService $crawlerService,
        CurrencyValidator $currencyValidator
    )
    {
        $this->currencyService = $currencyService;
        $this->crawlerService = $crawlerService;
        $this->currencyValidator = $currencyValidator;
    }

    public function get(Request $request)
    {
        try {
            $validated = $this->currencyValidator->validateInput($request);
            if(isset($validated['errors'])){
                return response()->json([
                    'error validation, acceptable formats' => $validated['formats']
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            
            $founds = $this->currencyService->get($validated);
            $crawler = [];
            if(!empty($founds['notFound'])){
                $crawler = $this->crawlerService->get($founds['notFound']);
            }

            $currencyDto = [];
            if(!empty($crawler)){
                $currencyDto = $this->currencyService->transformCrawlerToCurrenctyDTO($crawler);
                // TODO: PODE SER UMA FILA
                StoreCurrencyJob::dispatch($currencyDto)
                    ->delay(now()->addSeconds(10))
                    ->onQueue('storeCurrencies');
            }
            $data = array_merge($founds['found'], $currencyDto);
            return response()->json($data, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['errors' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }   
    }
}
