<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AttributeService;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(
        protected AttributeService $attributeService
    ) {}

    public function index()
    {
        $attributes = $this->attributeService->getAllAttributes();
        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:checkbox,select,radio',
            'is_filterable' => 'boolean',
            'sort_order' => 'integer|min:0',
            'values' => 'required|array|min:1',
            'values.*.value' => 'required|string|max:255',
            'values.*.color_code' => 'nullable|string|max:7'
        ]);

        $this->attributeService->createAttributeWithValues($request->validated());

        return redirect()->route('admin.attributes.index')
                        ->with('success', 'Attribute created successfully.');
    }

    public function show($id)
    {
        $attribute = $this->attributeService->getAttribute($id);
        
        if (!$attribute) {
            abort(404);
        }

        return view('admin.attributes.show', compact('attribute'));
    }

    public function edit($id)
    {
        $attribute = $this->attributeService->getAttribute($id);
        
        if (!$attribute) {
            abort(404);
        }

        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:checkbox,select,radio',
            'is_filterable' => 'boolean',
            'sort_order' => 'integer|min:0'
        ]);

        $this->attributeService->updateAttribute($id, $request->only(['name', 'type', 'is_filterable', 'sort_order']));

        return redirect()->route('admin.attributes.index')
                        ->with('success', 'Attribute updated successfully.');
    }

    public function destroy($id)
    {
        $this->attributeService->deleteAttribute($id);

        return redirect()->route('admin.attributes.index')
                        ->with('success', 'Attribute deleted successfully.');
    }

    public function storeValue(Request $request, $attributeId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'integer|min:0'
        ]);

        $this->attributeService->createAttributeValue($attributeId, $request->all());

        return redirect()->route('admin.attributes.show', $attributeId)
                        ->with('success', 'Attribute value added successfully.');
    }

    public function updateValue(Request $request, $attributeId, $valueId)
    {
        $request->validate([
            'value' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:7',
            'sort_order' => 'integer|min:0'
        ]);

        $this->attributeService->updateAttributeValue($valueId, $request->all());

        return redirect()->route('admin.attributes.show', $attributeId)
                        ->with('success', 'Attribute value updated successfully.');
    }

    public function destroyValue($attributeId, $valueId)
    {
        $this->attributeService->deleteAttributeValue($valueId);

        return redirect()->route('admin.attributes.show', $attributeId)
                        ->with('success', 'Attribute value deleted successfully.');
    }
}