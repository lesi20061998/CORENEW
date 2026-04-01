<?php

namespace App\Traits;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasTranslations
{
    public function translations(): MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    /**
     * Tự động lấy bản dịch khi truy cập attribute nếu đang ở locale khác mặc định.
     */
    public function getAttribute($key)
    {
        // 1. Chỉ xử lý các field được định nghĩa trong $translatableFields của Model
        if (isset($this->translatableFields) && in_array($key, $this->translatableFields)) {
            $currentLocale = \Illuminate\Support\Facades\App::getLocale();
            
            // 2. Lấy ngôn ngữ mặc định (cache 1h)
            $defaultLocale = \Illuminate\Support\Facades\Cache::remember('default_language_code', 3600, function() {
                return \App\Models\Language::where('is_default', true)->value('code') ?: config('app.fallback_locale');
            });

            // 3. Nếu không phải locale mặc định, thử tìm bản dịch
            if (strtolower($currentLocale) !== strtolower($defaultLocale)) {
                // Ensure translations are loaded
                if (!$this->relationLoaded('translations')) {
                    $this->load('translations');
                }

                $translation = $this->translations
                    ->where('locale', strtolower($currentLocale))
                    ->where('field', $key)
                    ->first();

                if ($translation && !empty($translation->value)) {
                    return $translation->value;
                }
            }
        }

        return parent::getAttribute($key);
    }

    /**
     * Lấy giá trị đã dịch của một field theo locale.
     * Fallback về giá trị gốc nếu không có bản dịch.
     */
    public function translate(string $field, string $locale): string
    {
        $translation = $this->translations
            ->where('locale', strtolower($locale))
            ->where('field', $field)
            ->first();

        return $translation?->value ?? ($this->$field ?? '');
    }

    /**
     * Lưu bản dịch cho nhiều field cùng lúc.
     * $data = ['vi' => ['name' => '...', 'description' => '...'], 'en' => [...]]
     */
    public function saveTranslations(array $data): void
    {
        foreach ($data as $locale => $fields) {
            foreach ($fields as $field => $value) {
                Translation::updateOrCreate(
                    [
                        'translatable_type' => static::class,
                        'translatable_id'   => $this->id,
                        'locale'            => $locale,
                        'field'             => $field,
                    ],
                    ['value' => $value]
                );
            }
        }
    }

    /**
     * Lấy tất cả bản dịch dạng ['vi' => ['name' => '...'], 'en' => [...]]
     */
    public function getTranslationsArray(array $fields): array
    {
        $result = [];
        foreach ($this->translations as $t) {
            if (in_array($t->field, $fields)) {
                $result[$t->locale][$t->field] = $t->value;
            }
        }
        return $result;
    }
    /**
     * Sao chép dữ liệu từ ngôn ngữ mặc định sang một ngôn ngữ khác (Translation)
     * Thường dùng để khởi tạo nhanh bản dịch Tiếng Anh từ Tiếng Việt.
     */
    public function duplicateToLocale(string $targetLocale): void
    {
        if (!isset($this->translatableFields)) return;

        $translations = [];
        foreach ($this->translatableFields as $field) {
            $value = $this->$field;
            
            // Nếu là slug thì thêm hậu tố ngôn ngữ để tránh trùng lặp
            if ($field === 'slug' && !empty($value)) {
                $value .= '-' . strtolower($targetLocale);
            }

            $translations[$targetLocale][$field] = $value;
        }

        $this->saveTranslations($translations);
    }
}
