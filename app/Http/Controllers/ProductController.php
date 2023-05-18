<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = Product::select('id', 'name', 'description', 'price', 'stock', 'brand_id', 'subcategory_id')
                ->where('status', Product::PUBLISH)
                ->limit(10)
                ->get()
                ->shuffle();

            return new ProductCollection($products);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function show(int $id)
    {
        try {
            $products = Product::find($id);

            return new ProductResource($products);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            return $this->response->success('creado', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function update(ProductRequest $request, int $id)
    {
        try {
            $product = Product::find($id);
            
            $product->update($request->validated());

            return $this->response->success('actualizado', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return $this->response->success('eliminado');
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
