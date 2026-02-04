<?php

namespace App\Providers;

use App\Models\Locale;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('partials.header', static function ($view) {
            $view->with(
                'locales',
                Locale::select('id', 'code', 'name', 'image')->get(),
            );
        });
    }
}
