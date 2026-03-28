<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Route;

// ─── Trang chủ ───────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ─── Shop ─────────────────────────────────────────────────────────────────────
Route::get('/shop',                [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/search-suggest', [ShopController::class, 'searchSuggest'])->name('shop.suggest');

// ─── Blog listing ─────────────────────────────────────────────────────────────
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');

// ─── Giỏ hàng ─────────────────────────────────────────────────────────────────
Route::get('/gio-hang',           [CartController::class, 'page'])->name('cart.page');
Route::post('/gio-hang/them',     [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/xoa',      [CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::get('/gio-hang/so-luong',  [CartController::class, 'count'])->name('cart.count');
Route::post('/gio-hang/xoa-het',  [CartController::class, 'clear'])->name('cart.clear');
Route::get('/gio-hang/dropdown',  [CartController::class, 'dropdown'])->name('cart.dropdown');

// ─── Thanh toán ───────────────────────────────────────────────────────────────
Route::get('/dat-hang',                          [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/dat-hang',                         [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/dat-hang/thanh-cong/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// ─── Liên hệ ──────────────────────────────────────────────────────────────────
Route::get('/lien-he',  [ContactController::class, 'index'])->name('contact.index');
Route::post('/lien-he', [ContactController::class, 'send'])->name('contact.send');

// ─── Auth ─────────────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

Route::prefix('tai-khoan')->name('account.')->middleware('auth')->group(function () {
    Route::get('/thong-tin',        [AuthController::class, 'profile'])->name('profile');
    Route::put('/thong-tin',        [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/don-hang',         [AuthController::class, 'orders'])->name('orders');
    Route::get('/don-hang/{order}', [AuthController::class, 'orderDetail'])->name('order.detail');
});

// ─── Static pages ─────────────────────────────────────────────────────────────
Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/faq',        fn() => view('pages.faq'))->name('faq');
Route::get('/theo-doi-don', fn() => view('pages.order-track'))->name('order.track');

// ─── Slug 1 cấp: product / post / page từ DB ─────────────────────────────────
// Phải đặt CUỐI CÙNG, loại trừ tất cả prefix đã dùng ở trên
Route::get('{slug}', [RouteController::class, 'index'])
    ->where('slug', '^(?!admin|shop|blog|gio-hang|dat-hang|lien-he|login|dang-nhap|dang-ky|register|dang-xuat|logout|tai-khoan|gioi-thieu|faq|theo-doi-don)[^/]+$')
    ->name('slug.show');

// Aliases để blade views dùng route('product.show') và route('blog.show') vẫn hoạt động
Route::get('san-pham/{slug}', fn(string $slug) => redirect("/$slug", 301))->name('product.show');
Route::get('bai-viet/{slug}', fn(string $slug) => redirect("/$slug", 301))->name('blog.show');
