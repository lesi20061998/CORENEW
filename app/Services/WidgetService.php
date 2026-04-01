<?php

namespace App\Services;

use App\Models\Widget;
use App\Repositories\WidgetRepository;
use App\Widgets\WidgetRegistry;
use Illuminate\Database\Eloquent\Collection;

class WidgetService
{
    public function __construct(protected WidgetRepository $repository) {}

    public function getAll(): Collection
    {
        return $this->repository->all();
    }

    public function forArea(string $area): Collection
    {
        return $this->repository->forArea($area);
    }

    public function find(int $id): ?Widget
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Widget
    {
        $data['config'] = $this->parseConfig($data['type'], $data['config'] ?? []);
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $widget = $this->repository->find($id);
        if ($widget) {
            $data['config'] = $this->parseConfig($widget->type, $data['config'] ?? []);
        }
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Render tất cả active widgets trong một area
     * Dùng trong Blade: @widgetArea('homepage')
     */
    public function renderArea(string $area): string
    {
        $html = '';
        foreach ($this->forArea($area) as $widget) {
            $html .= $this->render($widget) . "\n";
        }
        return $html;
    }

    /**
     * Render một widget đơn lẻ
     */
    public function render(Widget $widget): string
    {
        $class = WidgetRegistry::getClass($widget->type);

        if (!$class || !class_exists($class)) {
            // Fallback: thử blade view cũ
            $view = 'widgets.' . $widget->type;
            if (view()->exists($view)) {
                return view($view, ['widget' => $widget, 'data' => $widget->config ?? []])->render();
            }
            return "<!-- Widget type [{$widget->type}] not found -->";
        }

        return $class::render($widget->config ?? [], $widget);
    }

    /**
     * Lấy danh sách widget types đã đăng ký
     */
    public function registeredTypes(): array
    {
        return WidgetRegistry::types();
    }

    /**
     * Lấy danh sách widget areas
     */
    public function areas(): array
    {
        return WidgetRegistry::areas();
    }

    /**
     * Lấy widgets nhóm theo area
     */
    public function widgetsByArea(): array
    {
        $all   = $this->getAll();
        $areas = WidgetRegistry::areas();
        $result = [];

        foreach ($areas as $key => $area) {
            $result[$key] = [
                'area'    => $area,
                'widgets' => $all->where('area', $key)->sortBy('sort_order')->values(),
            ];
        }

        return $result;
    }

    /**
     * Cập nhật sort_order hàng loạt (dùng cho drag & drop)
     */
    public function reorder(array $items): void
    {
        // $items = [['id' => 1, 'area' => 'homepage', 'sort_order' => 0], ...]
        foreach ($items as $item) {
            $this->repository->update((int)$item['id'], [
                'area'       => $item['area'],
                'sort_order' => (int)$item['sort_order'],
            ]);
        }
    }

    private function parseConfig(string $type, array $raw): array
    {
        $class = WidgetRegistry::getClass($type);
        if (!$class || !class_exists($class)) {
            return $raw;
        }

        $fields = $class::fields();
        return $this->processFields($fields, $raw);
    }

    private function processFields(array $fields, array $raw): array
    {
        $config = [];
        foreach ($fields as $field) {
            $key = $field['key'];
            $val = $raw[$key] ?? ($field['default'] ?? null);

            if ($field['type'] === 'repeater' && is_array($val)) {
                $subFields = $field['fields'] ?? [];
                $config[$key] = array_map(
                    fn($row) => $this->processFields($subFields, $row),
                    $val
                );
            } elseif ($field['type'] === 'category_select') {
                $config[$key] = array_values(array_filter(array_map('intval', (array)$val)));
            } elseif ($field['type'] === 'toggle') {
                $config[$key] = filter_var($val, FILTER_VALIDATE_BOOLEAN);
            } elseif ($field['type'] === 'number') {
                $config[$key] = $val !== null ? (int)$val : null;
            } elseif ($field['type'] === 'box_model' && is_array($val)) {
                $config[$key] = array_map(fn($v) => is_numeric($v) ? (int)$v : $v, $val);
            } else {
                $config[$key] = $val;
            }
        }
        return $config;
    }
}
