<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductImage;
use App\Models\Size;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = ['admin_id','name','description','price','stock','category_id','is_published'];

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
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
    public function category() // Fonksiyon adı daha anlamlı oldu
    {
        return $this->belongsTo(Category::class);
    }

    public function getDiscountedPriceAttribute()
    {
        $price = $this->price;
        $now = Carbon::now();
        $productDiscount = $this->discounts()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
        if ($productDiscount) {
            if ($productDiscount->type === 'percentage') {
                $price = $price - ($price * ($productDiscount->value / 100));
            } else {
                $price = $price - $productDiscount->value;
            }
        }
        else {
            $categoryDiscount = Discount::where('category_id', $this->category_id)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->first();

            if ($categoryDiscount) {
                if ($categoryDiscount->type === 'percentage') {
                    $price = $price - ($price * ($categoryDiscount->value / 100));
                } else {
                    $price = $price - $categoryDiscount->value;
                }
            }
        }
        return max(0, $price);
    }
    public function decrementStock($quantity)
    {
        if ($this->stock < $quantity) {
            throw new \Exception('Yetersiz stok');
        }
        return $this->decrement('stock', $quantity);
    }
}
