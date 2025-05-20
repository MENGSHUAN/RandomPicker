<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WheelFeatureTest extends TestCase
{
    /** @test */
    public function wheel_page_can_be_rendered()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('轉盤抽籤');
    }

    /** @test */
    public function draw_api_returns_index()
    {
        $response = $this->postJson('/draw', ['count' => 12, 'draw_count' => 1]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['index']);
    }

    /** @test */
    public function draw_api_returns_error_when_count_less_than_1()
    {
        $response = $this->postJson('/draw', ['count' => 0]);
        $response->assertStatus(400);
        $response->assertJson(['error' => '選項數量需大於0']);
    }

    /** @test */
    public function set_options_api_returns_result_and_index()
    {
        $response = $this->postJson('/set-options', ['count' => 10]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['result', 'index']);
    }
} 