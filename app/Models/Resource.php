<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

abstract class Resource extends Model
{
    abstract public function translations();

    public function translation($localeId = null)
    {
        $localeId = $localeId ?? self::getCurrentLocaleId();
        $defaultLocaleId = config('app.default_locale_id', 1);

        return $this->translations
            ->where('locale_id', $localeId)
            ->first()
            ?? $this->translations
                ->where('locale_id', $defaultLocaleId)
                ->first();
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes) ||
            $this->hasGetMutator($key) ||
            $this->hasAttributeMutator($key) ||
            $key === 'translations') {
            return parent::__get($key);
        }

        $translation = $this->translation();

        if ($translation && isset($translation->$key)) {
            return $translation->$key;
        }

        return parent::__get($key);
    }

    public static function getCurrentLocaleId()
    {
        $currentLocaleCode = App::getLocale();

        return cache()->remember(
            "locale_id_{$currentLocaleCode}",
            3600,
            fn () => Locale::where('code', $currentLocaleCode)->value('id') ?? config('app.default_locale_id', 1)
        );
    }
}
