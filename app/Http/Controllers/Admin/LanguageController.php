<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::orderBy('sort_order')->get();
        return view('admin.languages.index', compact('languages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'        => 'required|string|max:10|unique:languages,code',
            'name'        => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
        ]);

        // Nếu chưa có ngôn ngữ nào thì set làm default
        if (Language::count() === 0) {
            $data['is_default'] = true;
        }

        Language::create($data);

        return back()->with('success', 'Đã thêm ngôn ngữ.');
    }

    public function update(Request $request, Language $language)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'native_name' => 'required|string|max:100',
            'flag'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]);

        $language->update($data);

        return back()->with('success', 'Đã cập nhật ngôn ngữ.');
    }

    public function setDefault(Language $language)
    {
        Language::query()->update(['is_default' => false]);
        $language->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', '"' . $language->name . '" đã được đặt làm ngôn ngữ mặc định.');
    }

    public function destroy(Language $language)
    {
        if ($language->is_default) {
            return back()->with('error', 'Không thể xóa ngôn ngữ mặc định.');
        }

        $language->delete();

        return back()->with('success', 'Đã xóa ngôn ngữ.');
    }
}
