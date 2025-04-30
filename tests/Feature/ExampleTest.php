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
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
