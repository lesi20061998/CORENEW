<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\FlashSaleService;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function __construct(protected FlashSaleService $service) {}

    public function index(Request $request)
    {
        $campaigns = $this->service->getPaginated(15, $request->only(['search', 'status']));
        return view('admin.flash-sales.index', compact('campaigns'));
    }

    public function create()
    {
        $products   = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'price', 'image']);
        $categories = Category::where('type', 'product')->orderBy('name')->get(['id', 'name']);
        return view('admin.flash-sales.form', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);
        $data['items'] = $this->parseItems($request);

        $campaign = $this->service->create($data);

        return redirect()->route('admin.flash-sales.show', $campaign)
                         ->with('success', 'Tạo chiến dịch flash sale thành công.');
    }

    public function show(int $id)
    {
        $campaign = $this->service->find($id);
        abort_if(!$campaign, 404);
        return view('admin.flash-sales.show', compact('campaign'));
    }

    public function edit(int $id)
    {
        $campaign   = $this->service->find($id);
        abort_if(!$campaign, 404);
        $products   = Product::where('status', 'active')->orderBy('name')->get(['id', 'name', 'price', 'image']);
        $categories = Category::where('type', 'product')->orderBy('name')->get(['id', 'name']);
        return view('admin.flash-sales.form', compact('campaign', 'products', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $data = $this->validateRequest($request);
        $data['items'] = $this->parseItems($request);

        $this->service->update($id, $data);

        return redirect()->route('admin.flash-sales.show', $id)
                         ->with('success', 'Cập nhật chiến dịch thành công.');
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);
        return redirect()->route('admin.flash-sales.index')
                         ->with('success', 'Đã xóa chiến dịch.');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at'   => 'required|date',
            'ends_at'     => 'required|date|after:starts_at',
            'status'      => 'required|in:draft,active,ended',
            'apply_to_all'=> 'nullable|boolean',
        ]);
    }

    private function parseItems(Request $request): array
    {
        $items = [];
        $raw = $request->input('items', []);
        foreach ($raw as $item) {
            if (empty($item['discount_value'])) continue;
            $items[] = [
                'product_id'     => !empty($item['product_id'])  ? (int)$item['product_id']  : null,
                'category_id'    => !empty($item['category_id']) ? (int)$item['category_id'] : null,
                'discount_type'  => $item['discount_type'] ?? 'percent',
                'discount_value' => (float)$item['discount_value'],
                'sale_limit'     => !empty($item['sale_limit']) ? (int)$item['sale_limit'] : null,
            ];
        }
        return $items;
    }
}
