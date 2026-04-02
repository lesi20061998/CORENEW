<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CustomerActionController;
use Illuminate\Support\Facades\Route;

// ─── CORE WEB ROUTES (SINGLE LOCALE MODE) ───────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route::get('/shop/{slug}', [App\Http\Controllers\ShopController::class, 'show'])->name('shop.show'); // Removed duplicate name
Route::post('/review/submit', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.submit');
Route::get('/cua-hang', [ShopController::class, 'index'])->name('shop.index');
Route::get('/cua-hang/search-suggest', [ShopController::class, 'searchSuggest'])->name('shop.suggest');
Route::get('/cua-hang/{category_slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/gio-hang', [CartController::class, 'page'])->name('cart.page');
Route::get('/gio-hang/so-luong', [CartController::class, 'count'])->name('cart.count');
Route::get('/gio-hang/dropdown', [CartController::class, 'dropdown'])->name('cart.dropdown');
Route::get('/gio-hang/tong', [CartController::class, 'total'])->name('cart.total');
Route::get('/dat-hang', [CheckoutController::class, 'index'])->name('checkout.index');
Route::get('/dat-hang/thanh-cong/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/lien-he', [ContactController::class, 'index'])->name('contact.index');

Route::prefix('tai-khoan')->middleware('auth')->group(function () {
    Route::get('/thong-tin', [AuthController::class, 'profile'])->name('profile');

    Route::get('/don-hang/{order}', [AuthController::class, 'orderDetail'])->name('order.detail');
});

Route::get('/gioi-thieu', fn() => view('pages.about'))->name('about');
Route::get('/faq', fn() => view('pages.faq'))->name('faq');
Route::get('/theo-doi-don', [CheckoutController::class, 'trackOrder'])->name('order.track');
Route::get('/wishlist', [CustomerActionController::class, 'wishlistIndex'])->name('wishlist');
Route::get('/so-sanh', [CustomerActionController::class, 'compareIndex'])->name('compare.index');
Route::get('/quick-view/{id}', [CustomerActionController::class, 'getQuickView'])->name('product.quickview');

// ─── ACTION ROUTES (POST, AUTH) ──────────────────────────────────────────

Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::post('/gio-hang/xoa-het', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/gio-hang/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/gio-hang/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::post('/dat-hang', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/lien-he', [ContactController::class, 'send'])->name('contact.send');
Route::put('/tai-khoan/thong-tin', [AuthController::class, 'updateProfile'])->name('account.profile.update');

require __DIR__ . '/auth.php';

// Action Routes
Route::post('/wishlist/add', [CustomerActionController::class, 'addToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove', [CustomerActionController::class, 'removeFromWishlist'])->name('wishlist.remove');
Route::post('/compare/add', [CustomerActionController::class, 'addToCompare'])->name('compare.add');
Route::post('/compare/remove', [CustomerActionController::class, 'removeFromCompare'])->name('compare.remove');

// ─── DYNAMIC SLUG ROUTE (MUST BE LAST) ───────────────────────────────────

Route::get('{slug}', [RouteController::class, 'index'])
    ->where('slug', '^(?!admin|cua-hang|blog|gio-hang|dat-hang|lien-he|login|dang-nhap|dang-ky|register|dang-xuat|logout|tai-khoan|gioi-thieu|faq|theo-doi-don|wishlist|so-sanh|quick-view|san-pham|bai-viet)[^/]+$')
    ->name('shop.show');

// Aliases
Route::get('san-pham/{slug}', fn(string $slug) => redirect("/$slug", 301))->name('product.show');
Route::get('bai-viet/{slug}', fn(string $slug) => redirect("/$slug", 301))->name('blog.show');

// Error Logic Testing
Route::prefix('test-errors')->group(function () {
    Route::get('/404', fn() => abort(404));
    Route::get('/500', fn() => abort(500));
});
