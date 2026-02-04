<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use App\Models\Subject;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function index($slug = 'index')
    {
        $view = $slug ?: 'index';

        if (! view()->exists($view)) {
            abort(404);
        }

        $locales = Locale::all();

        $defaultLocaleId = config('app.default_locale_id');
        $currentLocaleId = Locale::query()->where('code', App::currentLocale())->value('id') ?? $defaultLocaleId;

        $subjects = Subject::query()->whereIn('locale_id', [$defaultLocaleId, $currentLocaleId])
            ->orderByRaw("locale_id = $currentLocaleId DESC")
            ->get()
            ->unique('id');

        return view($view, compact('subjects', 'locales'));
    }
}
