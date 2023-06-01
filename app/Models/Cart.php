<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';

    protected $guarded = [];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
