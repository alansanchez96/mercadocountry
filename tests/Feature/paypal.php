<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class paypal extends TestCase
{
    public function test_paypal_process(): void
    {
        $response = $this->get('/api/paypal/process/' . 124);

        dd($response);
    }
}
