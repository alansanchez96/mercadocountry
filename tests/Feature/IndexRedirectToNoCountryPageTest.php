<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexRedirectToNoCountryPageTest extends TestCase
{
    public function testIndexRedirecciona(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('https://nocountry.tech/');
    }
}
