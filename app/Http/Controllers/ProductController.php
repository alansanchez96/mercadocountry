<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    protected $image;

    public function __construct(ImageService $image)
    {
        parent::__construct();
        $this->image = $image;
    }

    public function index()
    {
        try {
            $products = Product::select('id', 'name', 'slug', 'description', 'price', 'stock', 'brand_id', 'subcategory_id')
                ->where('status', Product::NEW)
                ->limit(10)
                ->get()
                ->shuffle();

            return new ProductCollection($products);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function show(string $slug)
    {
        try {
            $product = Product::firstWhere('slug', $slug);

            return new ProductResource($product);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function store(ProductRequest $request)
    {
        $productSlug = Str::lower(Str::slug($request->name));
        try {
            $product = Product::create([
                'name' => $request->name,
                'slug' => $productSlug,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'measures' => $request->measures,
                'brand_id' => $request->brand_id,
                'subcategory_id' => $request->subcategory_id,
                'status' => $request->status
            ]);

            if ($request->hasFile("image")) {
                foreach ($request->file('image') as $image) {
                    $this->image->create($image, $product);
                }
            }

            return $this->response->success('creado', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function update(Product $product, Request $request)
    {
        $productSlug = Str::lower(Str::slug($request->name));

        try {
            if ($request->hasFile("image")) {
                $this->image->delete($product);
                foreach ($request->file('image') as $image) {
                    $this->image->create($image, $product);
                }
            }

            $product->update([
                'name' => $request->name,
                'slug' => $productSlug,
                'description' => $request->description,
                'price' =>  $request->price,
                'stock' =>  $request->stock,
                'brand_id' =>  $request->brand_id,
                'subcategory_id' =>  $request->subcategory_id,
                'status' =>  $request->status,
            ]);

            return $this->response->success('actualizado', new ProductResource($product));
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->image->delete($product);

            $product->delete();

            return $this->response->success('eliminado');
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }
}
