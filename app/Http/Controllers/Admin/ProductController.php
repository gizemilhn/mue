<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\Size;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class ProductController extends Controller
{
    public function add_product()
    {
        $category = Category::all();
        $sizes = Size::all();
        return view('admin.products.store', compact('category', 'sizes'));
    }

    public function upload_product(Request $request)
    {
        $request->validate([
            'sizes.*.stock' => 'nullable|integer|min:0',
        ]);
        $product = new Product();
        $product->name = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->is_published = $request->is_published;
        $totalStock = 0;
        if ($request->has('sizes')) {
            foreach ($request->sizes as $data) {
                if (isset($data['selected'])) {
                    $totalStock += isset($data['stock']) ? (int)$data['stock'] : 0;
                }
            }
        }
        $product->stock = $totalStock;
        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imagename = time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('products/' . $product->id), $imagename);
                $product->images()->create([
                    'image_path' => 'products/' . $product->id . '/' . $imagename,
                    'is_featured' => $key == 0,
                ]);
            }
        }

        if ($request->has('sizes')) {
            foreach ($request->sizes as $size_id => $data) {
                if (isset($data['selected'])) {
                    $product->sizes()->attach($size_id, ['stock' => $data['stock'] ?? 0]);
                }
            }
        }

        toastr()->closeButton()->addSuccess('Ürün Başarıyla Eklendi');
        return redirect()->back();
    }

    public function view_product()
    {

        $product = Product::paginate(5);
        return view('admin.products.index', compact('product'));
    }

    public function delete_product($id)
    {

        $product = Product::find($id);
        if ($product) {
            $productFolder = public_path('products/' . $product->id);
            if (File::exists($productFolder)) {
                File::deleteDirectory($productFolder);
            }
            $product->delete();

            toastr()->closeButton()->success('Ürün Başarıyla Silindi');
        } else {
            toastr()->closeButton()->error('Ürün bulunamadı!');
        }

        return redirect()->back();
    }

    public function update_product($id)
    {

        $product = Product::with('images', 'sizes')->find($id);
        $category = Category::all();
        $sizes = Size::all();
        return view('admin.products.update', compact('product', 'category', 'sizes'));

    }

    public function edit_product(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category = $request->category;
        $product->is_published = $request->is_published;
        $product->save();
        $syncData = [];
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size_id => $data) {
                if (isset($data['selected'])) {
                    $syncData[$size_id] = ['stock' => $data['stock'] ?? 0];
                }
            }
        }
        $product->sizes()->sync($syncData);
        $request->validate([
            'sizes.*.stock' => 'nullable|integer|min:0',
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imagename = time() . '_' . $key . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('products/' . $product->id), $imagename);
                $product->images()->create([
                    'image_path' => 'products/' . $product->id . '/' . $imagename,
                    'is_featured' => false,
                ]);
            }
        }
        toastr()->closeButton()->addSuccess('Ürün başarıyla güncellendi.');
        return redirect()->route('admin.product.index', ['page' => request()->input('page', 1)]);

    }

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function setFeaturedImage($id)
    {
        $image = ProductImage::findOrFail($id);
        ProductImage::where('product_id', $image->product_id)->update(['is_featured' => false]);
        $image->is_featured = true;
        $image->save();

        return response()->json(['success' => true]);
    }

    public function product_search(Request $request)
    {
        $search = $request->input('search', '');
        $product = Product::where('name', 'like', '%' . $search . '%')
            ->orWhere('category', 'like', '%' . $search . '%')
            ->paginate(5);
        $product->appends(['search' => $search]);

        return view('admin.products.index', compact('product'));
    }

    public function updateProductStocks()
    {
        $products = DB::table('product_size')
            ->select('product_id', DB::raw('SUM(stock) as total_stock'))
            ->groupBy('product_id')
            ->get();

        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->product_id)
                ->update(['stock' => $product->total_stock]);
        }


        $outOfStockItems = DB::table('product_size')
            ->where('product_size.stock', '<=', 0)
            ->join('products', 'product_size.product_id', '=', 'products.id')
            ->join('sizes', 'product_size.size_id', '=', 'sizes.id')
            ->select(
                'products.name as product_name',
                'sizes.name as size_name',
                'product_size.stock as current_stock'
            )
            ->get();

        if ($outOfStockItems->isNotEmpty()) {
            $message = 'Stoklar güncellendi, ancak şu ürünler tükendi: ';
            $message .= $outOfStockItems->map(function ($item) {
                return "{$item->product_name} ({$item->size_name}) - Mevcut: {$item->current_stock}";
            })->implode(', ');

            toastr()->closeButton()->addWarning($message);
        } else {
            toastr()->closeButton()->addSuccess('Stoklar başarıyla güncellendi.');
        }

        return redirect()->back();
    }
}
