<?php

namespace App\Repositories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository
{
    public function __construct(
        protected Setting $model
    ) {}

    public function all(): Collection
    {
        return $this->model->orderBy('group')->orderBy('key')->get();
    }

    public function getByGroup(string $group): Collection
    {
        return $this->model->where('group', $group)
            ->orderBy('section')
            ->orderBy('sort_order')
            ->orderBy('key')
            ->get();
    }

    public function get(string $key, $default = null)
    {
        return $this->model->get($key, $default);
    }

    public function set(string $key, $value, string $group = 'general', string $type = 'text'): Setting
    {
        return $this->model->set($key, $value, $group, $type);
    }

    public function updateMultiple(array $settings): void
    {
        foreach ($settings as $key => $value) {
            $setting = $this->model->where('key', $key)->first();
            if ($setting) {
                $setting->update(['value' => $value]);
            }
        }
    }

    public function delete(string $key): bool
    {
        return $this->model->where('key', $key)->delete();
    }
}