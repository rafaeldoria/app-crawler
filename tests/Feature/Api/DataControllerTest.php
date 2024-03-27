<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataControllerTest extends TestCase
{
    /**
     * Test getting an API data.
     */
    public function test_data_controller_show(): void
    {
        $data = ['code_list' => [ "GBP", "GEL", "HKD", "123"]];
        $response = $this->postJson('/api/data', $data);

        $response->assertStatus(200)
            ->assertSee(['code','number','decimal','currency','currency_locations']);
    }
}
