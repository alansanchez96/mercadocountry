<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Http\Resources\SubcategoryCollection;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function index()
    {
        try {
            $subcategories = Subcategory::select('id', 'name', 'category_id')
                ->get();

            return new SubcategoryCollection($subcategories);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    public function show(string $categorySlug, string $subcategorySlug)
    {
        try {
            $subcategory =
                Subcategory::whereHas('category', fn ($q) => $q->where('slug', $categorySlug))
                ->where('slug', $subcategorySlug)
                ->firstOrFail();

            return new SubcategoryResource($subcategory);
        } catch (\Exception $e) {
            return $this->response->catch($e->getMessage());
        }
    }

    // public function store(SubcategoryRequest $request)
    // {
    //     $slugFormat = Str::lower(Str::slug($request->name));

    //     try {
    //         $subcategory = Subcategory::create([
    //             'name' => $request->name,
    //             'category_id' => $request->category_id,
    //             'slug' => $slugFormat
    //         ]);

    //         return $this->response->success('creado', new SubcategoryResource($subcategory));
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }

    // public function update(SubcategoryRequest $request, string $slug)
    // {
    //     $slugFormat = Str::lower(Str::slug($request->name));

    //     try {
    //         $category = Subcategory::select('id', 'name', 'slug')
    //             ->firstWhere('slug', $slug);

    //         $category->update([
    //             'name' => $request->name,
    //             'slug' => $slugFormat
    //         ]);

    //         return $this->response->success('actualizado', new SubcategoryResource($category));
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }

    // public function destroy(string $slug)
    // {
    //     try {
    //         $category = Subcategory::firstWhere('slug', $slug);

    //         $category->delete();

    //         return $this->response->success('eliminado');
    //     } catch (\Exception $e) {
    //         return $this->response->catch($e->getMessage());
    //     }
    // }
}
