<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\CategoryCollection;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Colleccion con 6 categorias ordenadas aleatoriamente 
            $categories = Category::select('id', 'name')
                ->with(['products', 'subcategory'])
                ->limit(6)
                ->get()
                ->shuffle();

            return new CategoryCollection($categories);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
