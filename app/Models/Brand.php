<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Product, Category, Image};

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * BelongToMany = Pertenece a Muchos
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    
    /**
     * hasMany = Tiene Muchos
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
