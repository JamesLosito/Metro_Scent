<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Add fillable fields if needed
    protected $fillable = ['product_id', 'name', 'price', 'description', 'image'];
    protected $primaryKey = 'product_id';
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('view_product', compact('product'));
    }
}
