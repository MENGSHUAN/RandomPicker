<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionsFeatureTest extends TestCase
{
    /** @test */
    public function options_page_can_be_rendered()
    {
        $response = $this->get('/options');
        $response->assertStatus(200);
        $response->assertSee('選項列表');
    }
} 