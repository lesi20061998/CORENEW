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
     * Lấy giá trị đã dịch của một field theo locale.
     * Fallback về giá trị gốc nếu không có bản dịch.
     */
    public function translate(string $field, string $locale): string
    {
        $translation = $this->translations
            ->where('locale', $locale)
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
}
