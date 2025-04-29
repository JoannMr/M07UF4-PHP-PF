<?php

namespace App\Http\Controllers;

class ProductViewController extends Controller
{
    public function index()
    {
        return view('products.index'); // Aquí cargaremos la tabla con Vue más adelante
    }
}
