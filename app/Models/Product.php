<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Add fillable fields if needed
    protected $fillable = ['name', 'price', 'description'];
}
