<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
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

    public function show(int $id)
    {
        try {
            $categories = Category::find($id);

            return new CategoryResource($categories);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
