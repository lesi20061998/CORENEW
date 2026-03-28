<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $languages = Language::active()->get();
        $locale    = $request->get('locale', $languages->where('is_default', false)->first()?->code ?? 'en');
        $search    = $request->get('search');

        // Lấy tất cả translations theo locale
        $query = Translation::where('locale', $locale);
        if ($search) {
            $query->where(fn($q) => $q->where('field', 'like', "%$search%")
                                      ->orWhere('value', 'like', "%$search%"));
        }
        $translations = $query->orderBy('translatable_type')->orderBy('field')->paginate(50)->withQueryString();

        return view('admin.translations.index', compact('languages', 'locale', 'translations', 'search'));
    }

    public function update(Request $request, Translation $translation)
    {
        $translation->update(['value' => $request->input('value', '')]);
        return response()->json(['ok' => true]);
    }

    public function bulkUpdate(Request $request)
    {
        $items = $request->input('translations', []);
        foreach ($items as $id => $value) {
            Translation::where('id', $id)->update(['value' => $value]);
        }
        return back()->with('success', 'Đã lưu bản dịch.');
    }
}
