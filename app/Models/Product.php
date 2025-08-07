<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;
use App\Models\Size;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['admin_id','name','description','price','stock','is_published'];

    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class,'product_id','id');
    }
    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size')->withPivot('stock');
    }
    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function decrementStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new \Exception('Yetersiz stok');
        }
        return $this->decrement('stock', $quantity);
    }
}
