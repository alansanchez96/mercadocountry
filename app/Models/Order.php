<?php

namespace App\Models;

use App\Models\{City, State, User, Product};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Columna  =>  'dispatch_address'
    const DOMICILIO     = 'A DOMICILIO';
    const SUCURSAL      = 'RETIRO EN SUCURSAL';

    // Columna  =>  'status'
    const PENDIENTE     = 'PENDIENTE';
    const RECIBIDO      = 'RECIBIDO';
    const ENVIADO       = 'ENVIADO';
    const ENTREGADO     = 'ENTREGADO';
    const CANCELADO     = 'CANCELADO';

    protected $guarded = ['id', 'status', 'created_at', 'updated_at'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
