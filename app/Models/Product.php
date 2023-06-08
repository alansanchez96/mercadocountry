<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Brand, Subcategory, Image};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    const NEW = 'NUEVO';
    const USED = 'USADO';

    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
        'measures',
        'status',
        'slug'
    ];

    /**
     * Producto pertenece a un Brand
     * 
     * @return BelongTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Producto pertenece a un Subcategory
     * 
     * @return BelongTo
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
