<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandCollection;

class BrandController extends Controller
{
    public function index()
    {
        try {
            $brands = Brand::select('id', 'name')
                ->get();

            return new BrandCollection($brands);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
