<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{Order, State, Image};

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name','cost','state_id'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
