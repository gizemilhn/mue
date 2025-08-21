<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use App\Models\Collection;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('order')->get();
        $collections = Collection::all();
        $instagramPosts = InstagramPost::all();

        return view('admin.home_page_settings.index', compact('banners', 'collections', 'instagramPosts'));
    }

    // Banner işlemleri
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'order' => 'required|integer|min:0',
        ]);

        $imagePath = $request->file('image')->store('home_banners', 'public');

        HomeBanner::create([
            'image_path' => $imagePath,
            'link' => $request->link,
            'order' => $request->order,
        ]);

        return redirect()->route('admin.content.index')->with('success', 'Banner başarıyla eklendi.');
    }

    public function destroyBanner(HomeBanner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return redirect()->route('admin.content.index')->with('success', 'Banner başarıyla silindi.');
    }

    // Koleksiyon işlemleri
    public function storeCollection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
        ]);

        $imagePath = $request->file('image')->store('collections', 'public');

        Collection::create([
            'name' => $request->name,
            'image_path' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.content.index')->with('success', 'Koleksiyon başarıyla eklendi.');
    }

    public function destroyCollection(Collection $collection)
    {
        Storage::disk('public')->delete($collection->image_path);
        $collection->delete();

        return redirect()->route('admin.content.index')->with('success', 'Koleksiyon başarıyla silindi.');
    }

    // Instagram Post işlemleri
    public function storeInstagramPost(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'required|url',
        ]);

        $imagePath = $request->file('image')->store('instagram_posts', 'public');

        InstagramPost::create([
            'image_path' => $imagePath,
            'link' => $request->link,
        ]);

        return redirect()->route('admin.content.index')->with('success', 'Instagram gönderisi başarıyla eklendi.');
    }

    public function destroyInstagramPost(InstagramPost $instagramPost)
    {
        Storage::disk('public')->delete($instagramPost->image_path);
        $instagramPost->delete();

        return redirect()->route('admin.content.index')->with('success', 'Instagram gönderisi başarıyla silindi.');
    }
}
