<?php

namespace Tests\Feature;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    /** @test */
    public function home_page_works()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /** @test */
    public function basic_assertion_works()
    {
        $this->assertTrue(true);
    }
}