<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['category_name', 'slug', 'image_path', 'main_category_id','is_homepage_visible',];

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'category_name');
    }
}
