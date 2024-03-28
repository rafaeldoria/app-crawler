<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Currency;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CurrencyControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    /**
     * Test getting an API data.
     */
    public function test_currency_controller_show(): void
    {
        $currency = Currency::factory(1)->create();
        Location::factory(1)->create();

        $data = ['code_list' => [ $currency[0]->code, "GEL", "HKD", "ANG"]];
        $response = $this->postJson('/api/crawler', $data);

        $response->assertStatus(200)
            ->assertSee(['code','number','decimal','currency','currency_locations']);
    }
}
