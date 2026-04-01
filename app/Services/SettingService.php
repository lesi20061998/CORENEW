<?php

namespace App\Services;

use App\Repositories\SettingRepository;
use Illuminate\Database\Eloquent\Collection;

class SettingService
{
    public function __construct(
        protected SettingRepository $repository
    ) {}

    public function getAllSettings(): Collection
    {
        return $this->repository->all();
    }

    public function getSettingsByGroup(string $group): Collection
    {
        return $this->repository->getByGroup($group);
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->repository->get($key, $default);
    }

    public function setSetting(string $key, $value, string $group = 'general', string $type = 'text')
    {
        return $this->repository->set($key, $value, $group, $type);
    }

    public function updateSettings(array $settings, string $group = 'general'): void
    {
        $this->repository->updateMultiple($settings, $group);
    }

    public function initializeDefaultSettings(): void
    {
        $defaults = [
            ['key' => 'site_name', 'value' => 'Kalles Store', 'group' => 'general', 'type' => 'text', 'label' => 'Site Name'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'general', 'type' => 'image', 'label' => 'Site Logo'],
            ['key' => 'currency', 'value' => 'VND', 'group' => 'general', 'type' => 'text', 'label' => 'Currency'],
            ['key' => 'currency_symbol', 'value' => '₫', 'group' => 'general', 'type' => 'text', 'label' => 'Currency Symbol'],
            ['key' => 'products_per_page', 'value' => '12', 'group' => 'shop', 'type' => 'text', 'label' => 'Products Per Page'],
        ];

        foreach ($defaults as $setting) {
            $this->repository->set(
                $setting['key'],
                $setting['value'],
                $setting['group'],
                $setting['type']
            );
        }
    }
}