<?php

namespace App\Models;

use App\Models\{Brand, Subcategory, Image};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    const PUBLISH = 'PUBLISH';
    const UNPUBLISH = 'UNPUBLISH';

    protected $guarded = ['id', 'created_at', 'updated_at'];

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

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
