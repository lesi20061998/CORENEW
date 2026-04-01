<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Services\ProductService;
use App\Services\AttributeService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService   $productService,
        protected AttributeService $attributeService,
        protected CategoryService  $categoryService,
    ) {}

    public function index(Request $request)
    {
        $products = $this->productService->getPaginatedProducts(20, $request->only(['category_id', 'category_ids', 'search', 'status']));
        $categories = $this->categoryService->getCategoryTree('product');
        $counts = $this->productService->getCounts();
        return view('admin.products.index', compact('products', 'categories', 'counts'));
    }

    public function create()
    {
        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getCategoryTree('product');
        $languages  = Language::active()->get();
        $allProducts = \App\Models\Product::active()->orderBy('name')->get(['id', 'name', 'price', 'image']);
        return view('admin.products.create', compact('attributes', 'categories', 'languages', 'allProducts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'nullable|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'cost_price'        => 'nullable|numeric|min:0',
            'sku'               => 'nullable|string|max:100',
            'stock'             => 'nullable|integer|min:0',
            'stock_status'      => 'nullable|in:in_stock,out_of_stock,backorder',
            'weight'            => 'nullable|string',
            'has_variants'      => 'boolean',
            'status'            => 'nullable|in:active,inactive,draft',
            'is_featured'       => 'boolean',
            'is_favorite'       => 'boolean',
            'is_best_seller'    => 'boolean',
            'image'             => 'nullable|string',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'exists:categories,id',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'slug'              => 'nullable|string|max:255',
            'seo_focus_keyword' => 'nullable|string|max:255',
            'robots_meta'       => 'nullable|array',
            'schema_json'       => 'nullable',
            'attributes'        => 'nullable|array',
            'variants'          => 'nullable|array',
            'translations'      => 'nullable|array',
            'combos'            => 'nullable|array',
            'combos.*.id'       => 'required|exists:products,id',
            'combos.*.price'    => 'nullable|numeric|min:0',
            'combos.*.discount_type'=> 'nullable|in:fixed,percent',
            'combos.*.discount_value'=> 'nullable|numeric|min:0',
            'combos.*.is_active'=> 'nullable|boolean',
            'combos.*.sort_order'=> 'nullable|integer',
        ]);

        $data['status'] = $data['status'] ?? 'active';
        $data['stock_status'] = $data['stock_status'] ?? 'in_stock';
        $hasVariants = $request->boolean('has_variants');
        $data['has_variants'] = $hasVariants;
        $data['images'] = $this->parseImagesRaw($request->images_raw);

        // Sản phẩm có biến thể: stock cha = tổng stock variants
        if ($hasVariants) {
            $data['stock'] = collect($request->input('variants', []))->sum('stock');
        } else {
            $data['stock'] = $request->input('stock', 0);
        }

        // Loại bỏ các key không phải cột DB (Relationship hoặc Form data không thuộc schema)
        $productData = array_diff_key($data, array_flip([
            'attributes', 'variants', 'translations', 'category_ids', 'combos', 'images'
        ]));

        $product = $this->productService->createProduct($productData);

        // Sync Categories
        $product->categories()->sync($request->input('category_ids', []));

        if ($hasVariants && $request->filled('variants')) {
            $this->syncVariants($product, $request->input('variants', []));
            // Cập nhật lại stock cha sau khi sync
            $product->update(['stock' => $product->variants()->sum('stock')]);
        } else {
            // Sản phẩm đơn giản: sync attributes
            $this->productService->syncAttributes($product, $request->input('attributes', []));
        }

        if ($request->filled('translations')) {
            $product->saveTranslations($request->input('translations'));
        }

        // Sync Combos
        if ($request->has('combos')) {
            $comboData = [];
            foreach ($request->input('combos') as $c) {
                $comboData[$c['id']] = [
                    'combo_price' => $c['price'] ?? 0,
                    'discount_type' => $c['discount_type'] ?? 'fixed',
                    'discount_value' => $c['discount_value'] ?? 0,
                    'is_active'   => $c['is_active'] ?? true,
                    'sort_order'  => $c['sort_order'] ?? 0
                ];
            }
            $product->combos()->sync($comboData);
        }

        return redirect()->route('admin.products.index')
                         ->with('success', 'Đã tạo sản phẩm thành công.');
    }

    public function show($id)
    {
        $product = $this->productService->getProduct($id);
        abort_if(!$product, 404);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productService->getProduct($id);
        abort_if(!$product, 404);

        $product->load(['productAttributes', 'variants.variantAttributes', 'translations']);

        $attributes = $this->attributeService->getAllAttributes();
        $categories = $this->categoryService->getCategoryTree('product');
        $languages  = Language::active()->get();
        $allProducts = \App\Models\Product::active()->where('id', '!=', $id)->orderBy('name')->get(['id', 'name', 'price', 'image']);

        $currentAttributes = [];
        foreach ($product->productAttributes as $pa) {
            $currentAttributes[$pa->attribute_id][] = $pa->attribute_value_id;
        }

        return view('admin.products.edit', compact('product', 'attributes', 'categories', 'languages', 'currentAttributes', 'allProducts'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'price'             => 'nullable|numeric|min:0',
            'compare_price'     => 'nullable|numeric|min:0',
            'cost_price'        => 'nullable|numeric|min:0',
            'sku'               => 'nullable|string|max:100',
            'stock'             => 'nullable|integer|min:0',
            'stock_status'      => 'nullable|in:in_stock,out_of_stock,backorder',
            'weight'            => 'nullable|string',
            'has_variants'      => 'boolean',
            'status'            => 'nullable|in:active,inactive,draft',
            'is_featured'       => 'boolean',
            'is_favorite'       => 'boolean',
            'is_best_seller'    => 'boolean',
            'image'             => 'nullable|string',
            'category_ids'      => 'nullable|array',
            'category_ids.*'    => 'exists:categories,id',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'slug'              => 'nullable|string|max:255',
            'seo_focus_keyword' => 'nullable|string|max:255',
            'robots_meta'       => 'nullable|array',
            'schema_json'       => 'nullable',
            'attributes'        => 'nullable|array',
            'variants'          => 'nullable|array',
            'translations'      => 'nullable|array',
            'combos'            => 'nullable|array',
            'combos.*.id'       => 'required|exists:products,id',
            'combos.*.price'    => 'nullable|numeric|min:0',
            'combos.*.discount_type'=> 'nullable|in:fixed,percent',
            'combos.*.discount_value'=> 'nullable|numeric|min:0',
            'combos.*.is_active'=> 'nullable|boolean',
            'combos.*.sort_order'=> 'nullable|integer',
        ]);

        $data['status'] = $data['status'] ?? 'active';
        $data['stock_status'] = $data['stock_status'] ?? 'in_stock';
        $hasVariants = $request->boolean('has_variants');
        $data['has_variants'] = $hasVariants;
        $data['images'] = $this->parseImagesRaw($request->images_raw);

        if ($hasVariants) {
            $data['stock'] = collect($request->input('variants', []))->sum('stock');
        } else {
            $data['stock'] = $request->input('stock', 0);
        }

        // Loại bỏ các key không phải cột DB (Relationship hoặc Form data không thuộc schema)
        $productData = array_diff_key($data, array_flip([
            'attributes', 'variants', 'translations', 'category_ids', 'combos', 'images'
        ]));

        $this->productService->updateProduct($id, $productData);
        $product = $this->productService->getProduct($id);
        
        // Sync Categories
        $product->categories()->sync($request->input('category_ids', []));

        if ($hasVariants && $request->filled('variants')) {
            $this->syncVariants($product, $request->input('variants', []));
            $product->update(['stock' => $product->variants()->sum('stock')]);
        } else {
            // Sản phẩm đơn giản: xóa variants cũ, sync attributes
            $product->variants()->delete();
            $this->productService->syncAttributes($product, $request->input('attributes', []));
        }

        if ($request->filled('translations')) {
            $product->saveTranslations($request->input('translations'));
        }

        // Sync Combos
        $comboData = [];
        if ($request->has('combos')) {
            foreach ($request->input('combos') as $c) {
                $comboData[$c['id']] = [
                    'combo_price' => $c['price'] ?? 0,
                    'discount_type' => $c['discount_type'] ?? 'fixed',
                    'discount_value' => $c['discount_value'] ?? 0,
                    'is_active'   => $c['is_active'] ?? true,
                    'sort_order'  => $c['sort_order'] ?? 0
                ];
            }
        }
        $product->combos()->sync($comboData);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function quickUpdate(Request $request, $id)
    {
        $product = $this->productService->getProduct($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
        }

        $data = $request->validate([
            'price'          => 'nullable|numeric|min:0',
            'stock'          => 'nullable|integer|min:0',
            'status'         => 'nullable|in:active,inactive,draft',
            'sort_order'     => 'nullable|integer',
            'is_featured'    => 'nullable|boolean',
            'is_favorite'    => 'nullable|boolean',
            'is_best_seller'  => 'nullable|boolean',
        ]);

        $this->productService->updateProduct($id, $data);
        session()->flash('success', 'Cập nhật nhanh thành công!');

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật nhanh thành công!',
            'product' => $product->refresh()
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'ids'          => 'required|array',
            'ids.*'        => 'exists:products,id',
            'price'        => 'nullable|numeric|min:0',
            'price_rule'   => 'nullable|in:fixed,inc_amount,dec_amount,inc_percent,dec_percent',
            'stock'        => 'nullable|integer',
            'stock_rule'   => 'nullable|in:fixed,inc,dec',
            'status'       => 'nullable|in:active,inactive,draft',
            'sort_order'   => 'nullable|integer',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $ids = $data['ids'];
        $priceVal  = $request->input('price');
        $priceRule = $request->input('price_rule', 'fixed');
        $stockVal  = $request->input('stock');
        $stockRule = $request->input('stock_rule', 'fixed');
        
        // Các cập nhật đơn giản khác
        $simpleUpdates = array_filter($request->only(['status', 'sort_order', 'is_featured', 'is_favorite', 'is_best_seller']), function($v) {
            return $v !== null && $v !== '';
        });

        foreach ($ids as $id) {
            $p = $this->productService->getProduct($id);
            if (!$p) continue;

            // Xử lý Giá theo Rule
            if ($priceVal !== null && $priceVal !== '') {
                $currentPrice = (float)$p->price;
                switch ($priceRule) {
                    case 'fixed':       $p->price = (float)$priceVal; break;
                    case 'inc_amount':  $p->price = $currentPrice + (float)$priceVal; break;
                    case 'dec_amount':  $p->price = max(0, $currentPrice - (float)$priceVal); break;
                    case 'inc_percent': $p->price = $currentPrice * (1 + (float)$priceVal / 100); break;
                    case 'dec_percent': $p->price = max(0, $currentPrice * (1 - (float)$priceVal / 100)); break;
                }
            }

            // Xử lý Kho theo Rule
            if ($stockVal !== null && $stockVal !== '') {
                $currentStock = (int)$p->stock;
                switch ($stockRule) {
                    case 'fixed': $p->stock = $stockVal; break;
                    case 'inc':   $p->stock = $currentStock + $stockVal; break;
                    case 'dec':   $p->stock = max(0, $currentStock - $stockVal); break;
                }
            }

            // Các cập nhật đơn giản khác
            if (!empty($simpleUpdates)) {
                foreach($simpleUpdates as $key => $val) {
                    $p->{$key} = $val;
                }
            }

            $p->save();

            // Sync danh mục nếu có
            if ($request->has('category_ids')) {
                $p->categories()->sync($request->input('category_ids'));
            }
        }

        $message = 'Đã cập nhật đồng loạt ' . count($ids) . ' sản phẩm.';
        session()->flash('success', $message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('admin.products.index')
                         ->with('success', 'Sản phẩm đã được chuyển vào thùng rác.');
    }

    public function trash()
    {
        $products = $this->productService->getTrashedProducts(20);
        $counts = $this->productService->getCounts();
        return view('admin.products.trash', compact('products', 'counts'));
    }

    public function restore($id)
    {
        $this->productService->restoreProduct($id);
        return redirect()->route('admin.products.trash')
                         ->with('success', 'Đã khôi phục sản phẩm.');
    }

    public function forceDelete($id)
    {
        $this->productService->forceDeleteProduct($id);
        return redirect()->route('admin.products.trash')
                         ->with('success', 'Đã xóa vĩnh viễn sản phẩm.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    protected function syncVariants($product, array $variantsData): void
    {
        $keepIds = [];

        foreach ($variantsData as $idx => $vData) {
            $variantId = $vData['id'] ?? null;

            $variant = $variantId
                ? ProductVariant::find($variantId)
                : new ProductVariant(['product_id' => $product->id]);

            if (!$variant) continue;

            $variant->fill([
                'product_id'    => $product->id,
                'sku'           => $vData['sku'] ?? null,
                'price'         => ($vData['price'] !== '' && $vData['price'] !== null) ? $vData['price'] : null,
                'compare_price' => ($vData['compare_price'] ?? '') !== '' ? $vData['compare_price'] : null,
                'image'         => $vData['image'] ?? null,
                'stock'         => $vData['stock'] ?? 0,
                'sort_order'    => $idx,
                'is_active'     => true,
            ])->save();

            // Sync variant attributes
            ProductVariantAttribute::where('variant_id', $variant->id)->delete();
            foreach ($vData['attributes'] ?? [] as $attrId => $attrValId) {
                ProductVariantAttribute::create([
                    'variant_id'          => $variant->id,
                    'attribute_id'        => $attrId,
                    'attribute_value_id'  => $attrValId,
                ]);
            }

            $keepIds[] = $variant->id;
        }

        // Xóa variants không còn trong danh sách
        $product->variants()->whereNotIn('id', $keepIds)->delete();
    }

    protected function parseImagesRaw(?string $raw): array
    {
        if (!$raw) return [];
        return array_values(array_filter(array_map('trim', explode("\n", $raw))));
    }
}
