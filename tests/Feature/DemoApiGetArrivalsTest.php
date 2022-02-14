<?php

namespace Tests\Feature;

use Tests\TestCase;

class DemoApiGetArrivalsTest extends TestCase
{
    public function test_api_demo_get_arrivals_data()
    {
        $response = $this->withHeaders([
            'X-API-KEY' => 'Gm638pb1jA',
        ])->get('/api/demo');

        $response->assertStatus(200)
            ->assertJson([
                'Success' => true,
            ]);
    }
}
