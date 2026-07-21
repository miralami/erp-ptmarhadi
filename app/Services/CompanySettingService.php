<?php

namespace App\Services;

use App\Models\CompanySetting;
use Illuminate\Support\Facades\Cache;

class CompanySettingService
{
    private const CACHE_KEY = 'company_settings';

    public function get(string $key, mixed $default = null): mixed
    {
        $setting = CompanySetting::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public function set(string $key, mixed $value): void
    {
        CompanySetting::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value],
        );

        Cache::forget(self::CACHE_KEY);
    }

    public function getAll(): array
    {
        return CompanySetting::pluck('value', 'key')->toArray();
    }

    public function saveAll(array $settings): void
    {
        foreach ($settings as $key => $value) {
            CompanySetting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value],
            );
        }

        Cache::forget(self::CACHE_KEY);
    }
}
