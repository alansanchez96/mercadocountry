<?php

namespace App\Http\Controllers;

use App\Services\JsonResponseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    protected $response;

    public function __construct()
    {
        $this->response = new JsonResponseService;
    }
}
