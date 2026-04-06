<?php

namespace App\Widgets\Types;

use App\Models\Widget as WidgetModel;
use App\Widgets\BaseWidget;

class TeamGridWidget extends BaseWidget
{
    public static string $label       = 'Team Members';
    public static string $description = 'Hiển thị danh sách đội ngũ chuyên gia.';
    public static string $icon        = 'fa-solid fa-people-group';

    public static function fields(): array
    {
        return [
            ['key' => 'title', 'label' => 'Tiêu đề', 'type' => 'text', 'default' => 'Đội Ngũ Chuyên Gia'],
            ['key' => 'subtitle', 'label' => 'Mô tả ngắn', 'type' => 'text', 'default' => ''],
            ['key' => 'items', 'label' => 'Thành viên', 'type' => 'repeater',
                'fields' => [
                    ['key' => 'name', 'label' => 'Tên', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'role', 'label' => 'Chức vụ', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'image', 'label' => 'Ảnh', 'type' => 'image', 'col' => 'col-md-6'],
                    ['key' => 'phone', 'label' => 'Số điện thoại', 'type' => 'text', 'col' => 'col-md-6'],
                    ['key' => 'link', 'label' => 'Link cá nhân', 'type' => 'text'],
                ]
            ],
        ];
    }

    public static function render(array $config, WidgetModel $widget): string
    {
        return static::view('widgets.types.team_grid', compact('config', 'widget'));
    }
}
