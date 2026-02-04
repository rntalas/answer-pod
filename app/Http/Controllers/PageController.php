<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Locale;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    public function index($slug = 'home')
    {
        $view = 'pages.' . ($slug ?: 'home');

        if (!view()->exists($view)) {
            abort(404);
        }

        $title = ucfirst(str_replace('-', ' ', $slug ?: 'home'));
        $locales = Locale::all();

        $localeId = Locale::where('code', App::currentLocale())->value('id');

        $subjects = Subject::where('locale_id', $localeId)->get();

        return view($view, compact('title', 'subjects', 'locales'));
    }
}
