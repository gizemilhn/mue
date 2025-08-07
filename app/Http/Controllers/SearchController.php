<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('q');

        $products = Product::with('featuredImage')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->paginate(12);

        return view('home.search_result', compact('products', 'keyword'));
    }
}
