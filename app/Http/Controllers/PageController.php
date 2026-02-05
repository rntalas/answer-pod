<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use App\Models\Subject;

class PageController extends Controller
{
    public function index($slug = 'index')
    {
        $view = $slug ?: 'index';

        if (! view()->exists($view)) {
            abort(404);
        }

        $locales = Locale::all();

        $subjects = Subject::with('translations')->get();

        return view($view, compact('subjects', 'locales'));
    }
}
