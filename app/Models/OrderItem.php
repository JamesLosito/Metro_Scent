<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderItem extends Model
{
    protected $table = 'order_items'; // explicitly state the table

    protected $fillable = [
        'order_id', 
        'cart_item_id', 
        'product_id', 
        'quantity', 
        'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
