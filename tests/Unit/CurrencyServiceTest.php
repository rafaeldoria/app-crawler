<?php

namespace Tests\Unit;

use Mockery;
use App\DTO\CurrencyDTO;
use App\Models\Currency;
use PHPUnit\Framework\TestCase;
use App\Services\CurrencyService;
use App\Repositories\CurrencyRepository;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class CurrencyServiceTest extends TestCase
{
    /**
     * A test service return currency dto all found in database.
     */
    public function test_get_currency_by_string(): void
    { 
        $mockRepository = Mockery::mock(CurrencyRepository::class);
        
        $mockRepository->shouldReceive('get')->andReturnUsing(function ($field, $value) {
            if ($value === 'GBP' || $value === 'GEL' || $value === 'HKD') {
                return (object)[
                    'code' => $value,
                    'number' => '100',
                    'decimal' => '2',
                    'currency' => 'Pound Sterling',
                    'location' => new class {
                        public function toArray()
                        {
                            return [
                                [
                                    'id' => 1,
                                    'location' => 'UK',
                                    'icon' => 'icon UK'
                                ]
                            ];
                        }
                    }
                ];
            } elseif ($value === '123') {
                return (object)[
                    'code' => 'USD',
                    'number' => $value,
                    'decimal' => '2',
                    'currency' => 'US Dollar',
                    'location' => new class {
                        public function toArray()
                        {
                            return [
                                [
                                    'id' => 1,
                                    'location' => 'USA',
                                    'icon' => 'icon USA'
                                ]
                            ];
                        }
                    }
                ];
            }
            return null;
        });

        $currencyService = new CurrencyService($mockRepository);

        $result = $currencyService->get(["GBP", "GEL", "HKD", "123"]);

        $dto1 = new CurrencyDTO('GBP', '100', '2', 'Pound Sterling');
        $dto1->transformDBLocations([
                [
                    "id" => 1,
                    "location" => "UK",
                    "icon" => "icon UK",
                ]
            ]
        );

        $dto2 = new CurrencyDTO('GEL', '100', '2', 'Pound Sterling');
        $dto2->transformDBLocations([
                [
                    "id" => 1,
                    "location" => "UK",
                    "icon" => "icon UK",
                ]
            ]
        );
        $dto3 = new CurrencyDTO('HKD', '100', '2', 'Pound Sterling');
        $dto3->transformDBLocations([
                [
                    "id" => 1,
                    "location" => "UK",
                    "icon" => "icon UK",
                ]
            ]
        );
        $dto4 = new CurrencyDTO('USD', '123', '2', 'US Dollar');
        $dto4->transformDBLocations([
                [
                    "id" => 1,
                    "location" => "USA",
                    "icon" => "icon USA",
                ]
            ]
        );
        $this->assertEquals([
            "notfound" => [],
            "found" => [$dto1,$dto2,$dto3,$dto4]
        ], $result); 
    }
}
