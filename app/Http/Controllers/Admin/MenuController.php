<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Unified Menu Builder Workspace (List + Editor)
     */
    public function index(Request $request, $id = null)
    {
        $currentLocale = $request->query('locale', 'vi');
        
        // Lấy tất cả menu và lọc bằng Collection cho an toàn (hỗ trợ cả các menu cũ chưa có field locale)
        $allMenus = \App\Models\Widget::where('type', 'menu')->orderBy('name')->get();
        $menus = $allMenus->filter(function($m) use ($currentLocale) {
            $lang = $m->config['locale'] ?? 'vi'; // Mặc định là 'vi' nếu chưa có
            return $lang === $currentLocale;
        });
        
        $menuId = $id ?? ($menus->first()?->id ?? null);
        $menu   = $menuId ? \App\Models\Widget::find($menuId) : null;

        // Nếu menu đang chọn không khớp ngôn ngữ hiện tại, thử tìm menu khác khớp
        if ($menu && ($menu->config['locale'] ?? 'vi') !== $currentLocale) {
            $menu = $menus->first();
        }

        $items = $menu ? ($menu->config['items'] ?? []) : [];

        // Lấy dữ liệu nguồn (Source) đã được dịch sẵn
        $mapTranslations = function($items, $labelField, $locale) {
            return $items->map(function($item) use ($labelField, $locale) {
                return [
                    'id'    => $item->id,
                    'type'  => $item->type ?? 'page',
                    'slug'  => $item->slug,
                    'label' => $item->translate($labelField, $locale) ?: $item->$labelField
                ];
            });
        };

        $categories = $mapTranslations(\App\Models\Category::with('translations')->where('is_active', true)->get(), 'name', $currentLocale);
        $pages      = $mapTranslations(\App\Models\Page::with('translations')->where('status', 'published')->get(), 'title', $currentLocale);
        $posts      = $mapTranslations(\App\Models\Post::with('translations')->where('status', 'published')->get(), 'title', $currentLocale);

        return view('admin.menus.builder', compact('menus', 'menu', 'items', 'categories', 'pages', 'posts', 'currentLocale'));
    }

    public function edit(Request $request, $id)
    {
        return $this->index($request, $id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'items'  => 'required|array',
            'locale' => 'required|string',
            'name'   => 'nullable|string',
            'area'   => 'nullable|string',
        ]);

        $menu = \App\Models\Widget::findOrFail($id);
        $config = $menu->config ?? [];
        $config['items']  = $request->items;
        $config['locale'] = $request->locale;

        $menu->update([
            'name'   => $request->name ?? $menu->name,
            'area'   => $request->area ?? $menu->area,
            'config' => $config
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Store simplified: Only name required
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'locale' => 'required|string|max:10',
        ]);

        $menu = \App\Models\Widget::create([
            'name'      => $request->name,
            'type'      => 'menu',
            'area'      => 'header_' . time(), // Tạm thời tạo area ngẫu nhiên
            'config'    => ['items' => [], 'locale' => $request->locale],
            'is_active' => true,
        ]);

        return redirect()->route('admin.menus.edit', ['id' => $menu->id, 'locale' => $request->locale])
                         ->with('success', 'Đã tạo menu mới.');
    }

    /**
     * AJAX: Search products for menu links
     */
    public function searchLinks(Request $request)
    {
        $q = $request->query('q');
        if (!$q) return response()->json([]);

        $results = [];

        // Products
        $products = DB::table('products')
            ->where('status', 'active')
            ->where('name', 'LIKE', "%$q%")
            ->limit(5)
            ->get(['id', 'name', 'slug']);
        foreach($products as $p) $results[] = ['label' => '[SP] ' . $p->name, 'url' => '/' . $p->slug];

        // Posts
        $posts = DB::table('posts')
            ->where('status', 'published')
            ->where('title', 'LIKE', "%$q%")
            ->limit(5)
            ->get(['id', 'title', 'slug']);
        foreach($posts as $p) $results[] = ['label' => '[BV] ' . $p->title, 'url' => '/' . $p->slug];

        return response()->json($results);
    }
    public function destroy($id)
    {
        $menu = \App\Models\Widget::where('type', 'menu')->findOrFail($id);
        $menu->delete();
        return response()->json(['success' => true]);
    }
}
