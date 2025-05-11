<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['full_name', 'email', 'address', 'total'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
