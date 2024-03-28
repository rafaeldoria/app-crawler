<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Services\CurrencyService;
use App\Repositories\CurrencyRepository;
use App\Repositories\LocationRepository;

class CurrencyServiceTest extends TestCase
{
    /**
     * A test service return currency dto all found in database by strings.
     */
    public function test_get_database_currency_by_code_list(): void
    { 
        $mockCurrencyRepository = Mockery::mock(CurrencyRepository::class);
        $mockLocationRepository = Mockery::mock(LocationRepository::class);
        
        $mockCurrencyRepository->shouldReceive('get')->andReturnUsing(function ($field, $value) {
            if ($value === 'GBP' || $value === 'GEL' || $value === 'HKD') {
                return [
                    'code' => $value,
                    'number' => '100',
                    'decimal' => '2',
                    'currency' => 'Pound Sterling',
                    'location' => [[
                        'id' => 1,
                        'location' => 'UK',
                        'icon' => 'icon UK'
                    ]]
                ];
            }
            return null;
        });

        $currencyService = new CurrencyService($mockCurrencyRepository,$mockLocationRepository);

        $result = $currencyService->get(["code_list" => ["GBP", "GEL", "HKD"]]);

        $data = [
            "notFound" => [],
            "found" => [[
                "code" => "GBP",
                "number" => 100,
                "decimal" => 2,
                "currency" => "Pound Sterling",
                "currency_locations" => [
                    [
                        "location" => "UK",
                        "icon" => "icon UK"
                    ]
                ]
            ],[
                "code" => "GEL",
                "number" => 100,
                "decimal" => 2,
                "currency" => "Pound Sterling",
                "currency_locations" => [
                    [
                        "location" => "UK",
                        "icon" => "icon UK"
                    ]
                ]
            ],[
                "code" => "HKD",
                "number" => 100,
                "decimal" => 2,
                "currency" => "Pound Sterling",
                "currency_locations" => [
                    [
                        "location" => "UK",
                        "icon" => "icon UK"
                    ]
                ]
            ]]
        ];

        $this->assertEquals($data, $result);
    }

    /**
     * A test service return currency dto all found in database by number.
     */
    public function test_get_database_currency_by_number(): void
    { 
        $mockCurrencyRepository = Mockery::mock(CurrencyRepository::class);
        $mockLocationRepository = Mockery::mock(LocationRepository::class);
        
        $mockCurrencyRepository->shouldReceive('get')->andReturnUsing(function ($field, $value) {
            if ($value === '123') {
                return [
                    'code' => 'USD',
                    'number' => $value,
                    'decimal' => '2',
                    'currency' => 'US Dollar',
                    'location' => [[
                        'id' => 1,
                        'location' => 'USA',
                        'icon' => 'icon USA'
                    ]],
                ];
            }
            return null;
        });

        $currencyService = new CurrencyService($mockCurrencyRepository,$mockLocationRepository);

        $result = $currencyService->get(["code" => "123"]);

        $data = [
            "notFound" => [],
            "found" => [[
                "code" => "USD",
                "number" => 123,
                "decimal" => 2,
                "currency" => "US Dollar",
                "currency_locations" => [
                    [
                        "location" => "USA",
                        "icon" => "icon USA"
                    ]
                ]
            ]]
        ];

        $this->assertEquals($data, $result);
    }

    /**
     * A test service return all not found results.
     */
    public function test_get_currency_by_code_list_not_found_in_database(): void
    { 
        $mockCurrencyRepository = Mockery::mock(CurrencyRepository::class);
        $mockLocationRepository = Mockery::mock(LocationRepository::class);
        $mockCurrencyRepository->shouldReceive('get')->andReturnUsing(function ($field, $value) {
            if ($value === 'HJK' || $value === 'ZXC' || $value === 'SDF') {
                return (object)[
                    'code' => $value,
                    'number' => '100',
                    'decimal' => '2',
                    'currency' => 'Pound Sterling',
                    'location' => new class {
                        public function toArray()
                        {
                            return [[
                                'id' => 1,
                                'location' => 'UK',
                                'icon' => 'icon UK'
                            ]];
                        }
                    }
                ];
            }
            return null;
        });

        $currencyService = new CurrencyService($mockCurrencyRepository,$mockLocationRepository);

        $result = $currencyService->get(["code_list" => ["CFB", "TGB", "POL"]]);

        $data = [
            "notFound" => [
                0 => "CFB",
                1 => "TGB",
                2 => "POL",
            ],
            "found" => []
        ];

        $this->assertEquals($data, $result);
    }
}
