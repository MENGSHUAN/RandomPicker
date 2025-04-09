<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;


    /**
     * 測試輪盤遊戲頁面是否可以正常訪問
     */
    public function test_wheel_page_returns_successful_response(): void
    {
        $response = $this->get('/wheel');
        $response->assertStatus(200);
    }

    /**
     * 測試老虎機遊戲頁面是否可以正常訪問
     */
    public function test_slot_page_returns_successful_response(): void
    {
        $response = $this->get('/slot');
        $response->assertStatus(200);
    }

    /**
     * 測試選項管理頁面是否可以正常訪問
     */
    public function test_options_page_returns_successful_response(): void
    {
        $response = $this->get('/options');
        $response->assertStatus(200);
    }
}
