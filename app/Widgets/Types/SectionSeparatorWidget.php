<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class SectionSeparatorWidget extends BaseWidget
{
    public static string $label       = 'Section Separator';
    public static string $description = 'A horizontal separator line.';
    public static string $icon        = 'fa-solid fa-minus';

    public static function fields(): array
    {
        return [];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.section_separator', compact('config', 'widget'));
    }
}
