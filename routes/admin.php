<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\WidgetController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FlashSaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);
        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        // Languages (nested under settings — phải đặt TRƯỚC /settings/{group})
        Route::get('/settings/languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::post('/settings/languages', [LanguageController::class, 'store'])->name('languages.store');
        Route::put('/settings/languages/{language}', [LanguageController::class, 'update'])->name('languages.update');
        Route::post('/settings/languages/{language}/set-default', [LanguageController::class, 'setDefault'])->name('languages.set-default');
        Route::delete('/settings/languages/{language}', [LanguageController::class, 'destroy'])->name('languages.destroy');
        // Translations (nested under settings — phải đặt TRƯỚC /settings/{group})
        Route::get('/settings/translations', [TranslationController::class, 'index'])->name('translations.index');
        Route::put('/settings/translations/{translation}', [TranslationController::class, 'update'])->name('translations.update');
        Route::post('/settings/translations/bulk', [TranslationController::class, 'bulkUpdate'])->name('translations.bulk');
        // Settings group (wildcard — đặt SAU các route cụ thể)
        Route::get('/settings/{group}', [SettingController::class, 'show'])->name('settings.group');
        Route::put('/settings/{group}', [SettingController::class, 'update'])->name('settings.group.update');

        // Products
        Route::patch('/products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulk-update');
        Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
        Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::patch('/products/{id}/quick-update', [ProductController::class, 'quickUpdate'])->name('products.quick-update');
        Route::resource('products', ProductController::class)->names([
            'index' => 'products.index',
            'create' => 'products.create',
            'store' => 'products.store',
            'show' => 'products.show',
            'edit' => 'products.edit',
            'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);

        // Product Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Attributes
        Route::resource('attributes', AttributeController::class)->names([
            'index' => 'attributes.index',
            'create' => 'attributes.create',
            'store' => 'attributes.store',
            'show' => 'attributes.show',
            'edit' => 'attributes.edit',
            'update' => 'attributes.update',
            'destroy' => 'attributes.destroy',
        ]);

        // Attribute Values
        Route::prefix('attributes/{attribute}')->name('attributes.')->group(function () {
            Route::post('/values', [AttributeController::class, 'storeValue'])->name('values.store');
            Route::put('/values/{value}', [AttributeController::class, 'updateValue'])->name('values.update');
            Route::delete('/values/{value}', [AttributeController::class, 'destroyValue'])->name('values.destroy');
        });

        // Posts
        Route::patch('/posts/{id}/quick-update', [PostController::class, 'quickUpdate'])->name('posts.quick-update');
        Route::get('/posts/trash', [PostController::class, 'trash'])->name('posts.trash');
        Route::post('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
        Route::delete('/posts/{id}/force-delete', [PostController::class, 'forceDelete'])->name('posts.force-delete');
        Route::resource('posts', PostController::class)->except(['show']);

        // Pages
        Route::resource('pages', PageController::class)->except(['show']);

        // Media Manager
        Route::get('/media', [MediaController::class, 'index'])->name('media.index');
        Route::post('/media', [MediaController::class, 'store'])->name('media.store');
        Route::post('/media/move', [MediaController::class, 'moveFiles'])->name('media.move');
        Route::post('/media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');
        Route::get('/media/picker', [MediaController::class, 'picker'])->name('media.picker');
        Route::post('/media/folder', [MediaController::class, 'createFolder'])->name('media.folder');
        Route::delete('/media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

        // Widgets — custom routes BEFORE resource to avoid conflicts
        Route::post('/widgets/reorder', [WidgetController::class, 'reorder'])->name('widgets.reorder');
        Route::post('/widgets/{id}/toggle', [WidgetController::class, 'toggle'])->name('widgets.toggle');
        Route::post('/widgets/{id}/clone', [WidgetController::class, 'clone'])->name('widgets.clone');
        Route::get('/widgets/{id}/data', [WidgetController::class, 'getData'])->name('widgets.data');
        Route::resource('widgets', WidgetController::class)->except(['show']);
        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/trash', [OrderController::class, 'trash'])->name('orders.trash');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');
        Route::post('/orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
        Route::delete('/orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::put('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.update-payment');
        Route::put('/orders/{order}/note', [OrderController::class, 'updateNote'])->name('orders.update-note');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
        // Flash Sale Campaigns
        Route::resource('flash-sales', FlashSaleController::class)->names([
            'index' => 'flash-sales.index',
            'create' => 'flash-sales.create',
            'store' => 'flash-sales.store',
            'show' => 'flash-sales.show',
            'edit' => 'flash-sales.edit',
            'update' => 'flash-sales.update',
            'destroy' => 'flash-sales.destroy',
        ]);
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        // Admin own account
        Route::get('/account', [UserController::class, 'account'])->name('account');
        Route::put('/account', [UserController::class, 'updateAccount'])->name('account.update');
    });
});