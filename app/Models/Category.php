<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Product, Brand, Subcategory, Image};

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * hasManyThrough = Tiene a muchos Productos a través de Subcategorias
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
