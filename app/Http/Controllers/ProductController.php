<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with('sizes', 'featuredImage')->findOrFail($id);
        return view('home.product_detail', compact('product'));
    }
}
