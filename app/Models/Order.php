<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    protected $fillable = [
        'full_name', 
        'email', 
        'address', 
        'total', 
        'stripe_payment_intent',
        'user_id',
        'payment_method',
        'status',
        'delivery_date',
        'processing_notes'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'order_items');
    }
}
