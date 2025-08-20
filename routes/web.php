<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;

use App\Http\Controllers\User\StripeController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\OrderController as UserOrderController;
use App\Http\Controllers\User\ReturnRequestController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\CouponController as UserCouponController;

use App\Http\Controllers\ContactController as HomeContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as HomeProductController;
use App\Http\Controllers\SearchController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [HomeController::class, 'index'])->name('index');


Route::get('/search', [SearchController::class, 'index'])->name('search.products');
Route::get('/category/{slug}', [HomeController::class, 'categoryProducts'])->name('category_products');
Route::get('/product/{id}', [HomeProductController::class, 'show'])->name('product_detail');
Route::get('about_us',[HomeController::class, 'about_us'])->name('about_us');
Route::get('new_products',[HomeController::class, 'new_products'])->name('new_products');
Route::get('featured_products',[HomeController::class, 'featured_products'])->name('featured_products');
Route::get('contact_us',[HomeController::class, 'contact_us'])->name('contact_us');
Route::post('/contact', [HomeContactController::class, 'store'])->name('contact.submit');

Route::middleware('auth')->namespace('App\Http\Controllers\User')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'home'])->name('home');
    Route::post('/add-to-cart/{product}', [CartController::class, 'addToCart'])->name('add_cart');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

    Route::get('/orders/{order}', [UserOrderController::class, 'orderDetails'])->name('user.order.details');
    Route::post('/user.order/{order}/cancel', [UserOrderController::class, 'cancel'])->name('user.order.cancel');
    Route::post('/return-request/{order}', [ReturnRequestController::class, 'store'])->name('return.request.store');
    Route::get('/orders', [UserOrderController::class, 'userOrders'])->name('user.orders');
    Route::get('/coupons', [UserCouponController::class, 'index'])->name('user.coupons');
    Route::get('/address', [AddressController::class, 'userAddress'])->name('user.address');
    Route::post('/address', [AddressController::class, 'storeAddress'])->name('user.address.store');
    Route::get('/edit_address/{id}/edit', [AddressController::class, 'editAddress'])->name('user.address.edit');
    Route::put('/edit_address/{id}', [AddressController::class, 'updateAddress'])->name('user.address.update');
    Route::delete('address/{id}', [AddressController::class, 'destroyAddress'])->name('user.address.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'storeAddress'])->name('checkout.address.store');
    Route::post('/checkout/stripe', [StripeController::class, 'checkout'])->name('checkout.stripe');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');

   // Route::get('/', [UserProfileController::class, 'edit'])->name('profile.edit');
   // Route::patch('/', [UserProfileController::class, 'update'])->name('profile.update');
   // Route::delete('/', [UserProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/deactivate', [UserProfileController::class, 'deactivate'])->name('profile.deactivate');
    Route::post('/activate', [UserProfileController::class, 'activate'])->name('profile.activate');
});




// Admin middleware grubu
Route::middleware('auth')->middleware('admin')->middleware('verified')->prefix('admin')->namespace('App\Http\Controllers\Admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


    Route::get('/admin/global-search', [AdminSearchController::class, 'search'])->name('admin.global-search');

    Route::get('/admin/categories/main_category_index', [CategoryController::class, 'main_category_index'])->name('main.category.index');
    Route::post('/add_main_category', [CategoryController::class, 'add_main_category'])->name('add.main.category');
    Route::delete('/delete_main_category/{id}', [CategoryController::class, 'delete_main_category'])->name('delete.main.category');
    Route::get('/admin/categories/main_category_edit/{id}', [CategoryController::class, 'main_category_edit'])->name('main.category.edit');
    Route::post('/update_main_category/{id}', [CategoryController::class, 'update_main_category'])->name('update.main.category');

    Route::get('/admin/categories/index', [CategoryController::class, 'category_index'])->name('category.index');
    Route::post('add_category', [CategoryController::class, 'add_category'])->name('add.category');
    Route::get('delete_category/{id}', [CategoryController::class, 'delete_category'])->name('delete.category');
    Route::get('/admin/categories/edit/{id}', [CategoryController::class, 'category_edit'])->name('category.edit');
    Route::post('update_category/{id}', [CategoryController::class, 'update_category'])->name('update.category');

    Route::get('/admin/products/store', [AdminProductController::class, 'add_product'])->name('admin.products.store');
    Route::post('upload_product', [AdminProductController::class, 'upload_product'])->name('upload_product');
    Route::get('/admin/products/index', [AdminProductController::class, 'view_product'])->name('admin.products.index');
    Route::get('delete_product/{id}', [AdminProductController::class, 'delete_product'])->name('delete_product');
    Route::get('/admin/products/update/{id}', [AdminProductController::class, 'update_product'])->name('update_product');
    Route::post('edit_product/{id}', [AdminProductController::class, 'edit_product'])->name('edit_product');
    Route::delete('/delete_product_image/{id}', [AdminProductController::class, 'deleteImage'])->name('delete_product_image');
    Route::post('/set_featured_image/{id}', [AdminProductController::class, 'setFeaturedImage'])->name('set_featured_image');
    Route::get('product_search', [AdminProductController::class, 'product_search'])->name('product_search');
    Route::post('/admin/update-product-stocks', [AdminProductController::class, 'updateProductStocks'])->name('admin.updateProductStocks');

    Route::get('/admin/users/index', [UserController::class, 'users'])->name('admin.users.index');
    Route::post('/deactivate/{userId}', [UserController::class, 'deactivate'])->name('admin.deactivate');
    Route::post('/activate/{userId}', [UserController::class, 'activate'])->name('admin.activate');
    Route::delete('/delete/{userId}', [UserController::class, 'delete'])->name('admin.delete');
    Route::get('/admin/users/create', [UserController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users_store', [UserController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/{user}', [UserController::class, 'updateUser'])->name('admin.users.update');

    Route::post('/order/{order}/approve', [AdminOrderController::class, 'approve'])->name('order.approve');
    Route::post('/admin.orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('admin.order.cancel');
    Route::get('/admin/orders/index', [AdminOrderController::class, 'index'])->name('admin.orders.index');

    Route::get('/admin/shippings/index', [ShippingController::class, 'index'])->name('admin.shippings.index');
    Route::put('/admin/shippings/index/{shipping}/update-status', [ShippingController::class, 'updateShippingStatus'])->name('admin.shippings.updateStatus');
    Route::put('/admin/shippings/index/{shipping}/update-tracking', [ShippingController::class, 'updateShippingTracking'])->name('admin.shippings.updateTracking');

    Route::get('/admin/returns/index', [ReturnController::class, 'index'])->name('admin.returns.index');
    Route::get('/admin/returns/show/{id}', [ReturnController::class, 'show'])->name('admin.returns.show');
    Route::post('/returns/{id}/approve', [ReturnController::class, 'approve'])->name('admin.returns.approve');
    Route::post('/returns/{id}/reject', [ReturnController::class, 'reject'])->name('admin.returns.reject');
    Route::post('/returns/{id}/complete', [ReturnController::class, 'complete'])->name('admin.returns.complete');

    Route::get('/admin/contacts/index', [AdminContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('/admin/contacts/show/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');

    Route::get('/admin/coupons/index', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::get('/admin/coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('/admin/coupons/store', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('/admin/coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/{coupon}', [CouponController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/{coupon}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');

    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
