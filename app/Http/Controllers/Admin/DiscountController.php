<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::with('product', 'category')->get();
        $products = Product::all();
        $categories = Category::all();
        return view('admin.discounts.index', compact('discounts', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Discount::create([
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'product_id' => $request->product_id,
            'category_id' => $request->category_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        toastr()->closeButton()->addSuccess('İndirim başarıyla eklendi.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        toastr()->closeButton()->addSuccess('İndirim başarıyla silindi.');
        return redirect()->back();
    }
}
