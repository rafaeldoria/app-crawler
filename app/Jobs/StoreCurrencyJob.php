<?php

namespace App\Jobs;

use App\Services\CurrencyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreCurrencyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $currencyDto)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(CurrencyService $currencyService): void
    {
        $currencyService->store($this->currencyDto);
    }
}
