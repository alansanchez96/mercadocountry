<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\Categories\CategoryResource;
use App\Http\Resources\Categories\CategoryCollection;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = Category::select('id', 'name', 'slug')
                ->with(['subcategory'])
                ->get();

            return new CategoryCollection($categories);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function show(string $slug)
    {
        try {
            $category = Category::with('subcategory')
                ->firstWhere('slug', $slug);

            return new CategoryResource($category);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    // public function store(CategoryRequest $request)
    // {
    //     $slugFormat = Str::lower(Str::slug($request->name));

    //     try {
    //         $category = Category::create([
    //             'name' => $request->name,
    //             'slug' => $slugFormat
    //         ]);

    //         return $this->response->success('creado', new CategoryResource($category));
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }

    // public function update(CategoryRequest $request, string $slug)
    // {
    //     $slugFormat = Str::lower(Str::slug($request->name));

    //     try {
    //         $category = Category::select('id', 'name', 'slug')
    //             ->firstWhere('slug', $slug);

    //         $category->update([
    //             'name' => $request->name,
    //             'slug' => $slugFormat
    //         ]);

    //         return $this->response->success('actualizado', new CategoryResource($category));
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }

    // public function destroy(string $slug)
    // {
    //     try {
    //         $category = Category::firstWhere('slug', $slug);

    //         $category->delete();

    //         return $this->response->success('eliminado');
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }
}
