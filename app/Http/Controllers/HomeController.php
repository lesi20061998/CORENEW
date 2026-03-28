<?php

namespace App\Http\Controllers;

use App\Services\WidgetService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected WidgetService $widgetService) {}

    public function index()
    {
        return view('index');
    }
}
