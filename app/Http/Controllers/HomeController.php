<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Collection;
use App\Models\HomeBanner;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    public function index()
    {
        $banners = HomeBanner::orderBy('order')->get();
        $collections = Collection::all();
        $instagramPosts = InstagramPost::all();
        $categories = Category::all();
        $latestProducts = Product::with('featuredImage')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $topStockProducts = Product::with('featuredImage')
            ->orderByDesc('stock')
            ->take(5)
            ->get();

        return view('home.home', compact('latestProducts', 'topStockProducts', 'categories', 'collections', 'instagramPosts', 'banners'));
    }
    public function home()
    {
        $user= Auth::user();
        $userid= $user->id;
        $count = Cart::where('user_id',$userid)->count();
        $categories = Category::all();
        $latestProducts = Product::with('featuredImage')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $topStockProducts = Product::with('featuredImage')
            ->orderByDesc('stock')
            ->take(5)
            ->get();

        return view('home.home', compact('latestProducts', 'topStockProducts', 'categories','count'));
    }
    public function about_us()
    {
        return view('home.about_us');
    }

    public function new_products(Request $request)
    {
        $sort = $request->get('sort');
        $productIdsQuery = Product::orderBy('created_at', 'desc')->limit(18);
        $productIds = $productIdsQuery->pluck('id');
        $query = Product::with('featuredImage')
            ->whereIn('id', $productIds);

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->get();
        $perPage = 9;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $pagedProducts = $products->slice($offset, $perPage);
        $paginatedProducts = new LengthAwarePaginator(
            $pagedProducts,
            $products->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('home.new_products', [
            'products' => $paginatedProducts,
            'sort' => $sort
        ]);
    }
    public function featured_products(Request $request)
    {
        $sort = $request->get('sort');
        $productIdsQuery = Product::orderBy('stock', 'desc')->limit(18);
        $productIds = $productIdsQuery->pluck('id');
        $query = Product::with('featuredImage')
            ->whereIn('id', $productIds);
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->get();
        $perPage = 9;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $pagedProducts = $products->slice($offset, $perPage);
        $paginatedProducts = new LengthAwarePaginator(
            $pagedProducts,
            $products->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('home.featured_products', [
            'products' => $paginatedProducts,
            'sort' => $sort
        ]);
    }
    public function categoryProducts($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $sort = request()->get('sort');

        $query = Product::with('featuredImage')->where('category', $category->category_name);

        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9);

        return view('home.category_products', compact('products', 'category'));
    }

    public function contact_us()
    {
        return view('home.contact_us');
    }


}
