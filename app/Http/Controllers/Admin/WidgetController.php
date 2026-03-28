<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WidgetService;
use App\Widgets\WidgetRegistry;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function __construct(protected WidgetService $widgetService) {}

    public function index()
    {
        $types         = $this->widgetService->registeredTypes();
        $areas         = $this->widgetService->areas();
        $widgetsByArea = $this->widgetService->widgetsByArea();
        $categories    = \App\Models\Category::where('is_active', true)->orderBy('name')->get(['id','name']);

        return view('admin.widgets.index', compact('types', 'areas', 'widgetsByArea', 'categories'));
    }

    public function create(Request $request)
    {
        $types       = $this->widgetService->registeredTypes();
        $areas       = $this->widgetService->areas();
        $defaultArea = $request->query('area', 'homepage');
        $defaultType = $request->query('type', '');

        return view('admin.widgets.create', compact('types', 'areas', 'defaultArea', 'defaultType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|string',
            'area'       => 'required|string',
            'sort_order' => 'integer|min:0',
        ]);

        $types = $this->widgetService->registeredTypes();
        $name  = $types[$request->type]['label'] ?? $request->type;

        $this->widgetService->create(
            ['name' => $name] + $request->only(['type', 'area', 'sort_order', 'is_active'])
            + ['config' => $request->input('config', [])]
        );

        return redirect()->route('admin.widgets.index')
            ->with('success', 'Đã tạo widget thành công.');
    }

    public function edit(int $id)
    {
        $widget = $this->widgetService->find($id) ?? abort(404);
        $types  = $this->widgetService->registeredTypes();
        $areas  = $this->widgetService->areas();

        return view('admin.widgets.edit', compact('widget', 'types', 'areas'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'area'       => 'required|string',
            'sort_order' => 'integer|min:0',
        ]);

        $widget = $this->widgetService->find($id) ?? abort(404);
        $types  = $this->widgetService->registeredTypes();
        $name   = $types[$widget->type]['label'] ?? $widget->type;

        $this->widgetService->update(
            $id,
            ['name' => $name] + $request->only(['area', 'sort_order', 'is_active'])
            + ['config' => $request->input('config', [])]
        );

        return redirect()->route('admin.widgets.index')
            ->with('success', 'Đã cập nhật widget.');
    }

    public function destroy(int $id)
    {
        $this->widgetService->delete($id);
        return redirect()->route('admin.widgets.index')
            ->with('success', 'Đã xóa widget.');
    }

    /**
     * AJAX: Cập nhật sort_order và area sau khi drag & drop
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items'              => 'required|array',
            'items.*.id'         => 'required|integer',
            'items.*.area'       => 'required|string',
            'items.*.sort_order' => 'required|integer',
        ]);

        $this->widgetService->reorder($request->input('items'));

        return response()->json(['success' => true]);
    }

    /**
     * AJAX: Toggle is_active
     */
    public function toggle(int $id)
    {
        $widget = $this->widgetService->find($id) ?? abort(404);
        $this->widgetService->update($id, ['is_active' => !$widget->is_active]);

        return response()->json(['is_active' => !$widget->is_active]);
    }

    /**
     * AJAX: Trả về dữ liệu widget để modal edit
     */
    public function getData(int $id)
    {
        $widget = $this->widgetService->find($id) ?? abort(404);
        return response()->json([
            'id'         => $widget->id,
            'name'       => $widget->name,
            'type'       => $widget->type,
            'area'       => $widget->area,
            'sort_order' => $widget->sort_order,
            'is_active'  => $widget->is_active,
            'config'     => $widget->config ?? [],
        ]);
    }

    /**
     * AJAX: Clone widget
     */
    public function clone(int $id)
    {
        $widget = $this->widgetService->find($id) ?? abort(404);
        $this->widgetService->create([
            'name'       => $widget->name . ' (copy)',
            'type'       => $widget->type,
            'area'       => $widget->area,
            'config'     => $widget->config ?? [],
            'sort_order' => $widget->sort_order + 1,
            'is_active'  => false,
        ]);

        return response()->json(['success' => true]);
    }
}
