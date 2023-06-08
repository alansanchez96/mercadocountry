<?php

use Illuminate\Support\Facades\Route;

/**
 * Redireccionamos a la URL para que la API no tenga acceso
 */
Route::get('/', function () {
    return view('pay');
});
