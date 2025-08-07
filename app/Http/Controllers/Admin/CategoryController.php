<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function main_category_index()
    {
        $mainCategories = MainCategory::all();
        return view('admin.categories.main_category_index', compact('mainCategories'));
    }

    public function add_main_category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:main_categories,name',
        ]);

        MainCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        toastr()->closeButton()->addSuccess('Ana Kategori Başarıyla Eklendi');
        return redirect()->back();
    }

    public function main_category_edit($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        return view('admin.categories.main_category_edit', compact('mainCategory'));
    }

    public function update_main_category(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:main_categories,name,' . $id,
        ]);

        $mainCategory = MainCategory::findOrFail($id);
        $mainCategory->name = $request->name;
        $mainCategory->slug = Str::slug($request->name); // Slug'ı da güncelliyoruz
        $mainCategory->save();

        toastr()->closeButton()->addSuccess('Ana Kategori Başarıyla Güncellendi');
        return redirect()->route('main.category.index');
    }

    public function delete_main_category($id)
    {
        $mainCategory = MainCategory::findOrFail($id);

        if ($mainCategory->categories()->count() > 0) {
            toastr()->closeButton()->addError('Bu ana kategoriye bağlı alt kategoriler var, önce onları silmelisiniz');
            return redirect()->back();
        }

        $mainCategory->delete();
        toastr()->closeButton()->success('Ana Kategori Başarıyla Silindi');
        return redirect()->back();
    }

    public function category_index()
    {
        $mainCategories = MainCategory::all();
        $categories = Category::all();
        return view('admin.categories.index', compact('categories', 'mainCategories'));
    }

    public function add_category(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_category_id' => 'nullable|exists:main_categories,id'
        ]);

        $category = new Category();
        $category->category_name = $request->category;
        $category->slug = Str::slug($request->category);
        $category->main_category_id = $request->main_category_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image_path = $imagePath;
        }

        $category->save();

        toastr()->closeButton()->addSuccess('Kategori Başarıyla Eklendi');
        return redirect()->back();
    }

    public function delete_category($id)
    {

        $category = Category::find($id);
        $category->delete();
        toastr()->closeButton()->success('Kategori Başarıyla Silindi');
        return redirect()->back();
    }

    public function category_edit($id)
    {
        $category = Category::find($id);
        $mainCategories = MainCategory::all();
        return view('admin.categories.edit_category', compact('category', 'mainCategories'));

    }

    public function update_category(Request $request, $id)
    {
        $category = Category::find($id);

        $request->validate([
            'category' => 'required|string|max:255',
            'main_category_id' => 'nullable|exists:main_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category->category_name = $request->category;
        $category->slug = Str::slug($request->category);
        $category->main_category_id = $request->main_category_id;

        if ($request->hasFile('image')) {

            if ($category->image_path && Storage::disk('public')->exists($category->image_path)) {
                Storage::disk('public')->delete($category->image_path);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image_path = $imagePath;
        }

        $category->save();

        toastr()->timeOut(10000)->closeButton()->addSuccess('Kategori Başarıyla Güncellendi');
        return route('category.index');
    }
}
