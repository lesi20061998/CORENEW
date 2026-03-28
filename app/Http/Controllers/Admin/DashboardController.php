<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\AttributeService;

class DashboardController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected AttributeService $attributeService
    ) {}

    public function index()
    {
        $stats = [
            'total_products' => $this->productService->getAllProducts()->count(),
            'active_products' => $this->productService->getActiveProducts()->count(),
            'total_attributes' => $this->attributeService->getAllAttributes()->count(),
            'filterable_attributes' => $this->attributeService->getFilterableAttributes()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}