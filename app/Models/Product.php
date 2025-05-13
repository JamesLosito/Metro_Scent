<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    // Add fillable fields if needed
    protected $fillable = ['product_id', 'name', 'price', 'description', 'image', 'type', 'stock'];
    protected $primaryKey = 'product_id';

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Check if the image path is already a full URL
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            
            // Check if the image exists in storage
            if (Storage::disk('public')->exists($this->image)) {
                return Storage::url($this->image);
            }
            
            // Check if the image exists in the public directory
            $publicPath = 'images/' . strtoupper($this->type) . '/' . $this->image;
            if (file_exists(public_path($publicPath))) {
                return asset($publicPath);
            }
        }
        
        // Return default image based on type
        return asset('images/no-image.png');
    }

    public function storeImage($image)
    {
        if ($image) {
            // Create type-specific folder path
            $folder = 'products/' . $this->type;
            
            // Delete old image if exists
            if ($this->image) {
                $this->deleteImage();
            }
            
            // Store new image in type-specific folder
            $path = $image->store($folder, 'public');
            $this->image = $path;
            $this->save();
            
            return $path;
        }
        return null;
    }

    public function deleteImage()
    {
        if ($this->image) {
            // Try to delete from storage
            if (Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
            }
            
            // Try to delete from public directory
            $publicPath = public_path('images/' . strtoupper($this->type) . '/' . $this->image);
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
            
            $this->image = null;
            $this->save();
        }
    }

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
