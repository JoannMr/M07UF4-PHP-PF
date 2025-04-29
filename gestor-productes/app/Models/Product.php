<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 👇 Esto es lo que te faltaba:
    protected $fillable = [
        'name',
        'description',
        'price'
    ];
}
