<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Product;
use Tests\TestCase;
use App\Models\Order;

class IndexRedirectToNoCountryPageTest extends TestCase
{
    public function testIndexRedirecciona(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('https://nocountry.tech/');
    }

}
