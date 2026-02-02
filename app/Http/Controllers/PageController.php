<?php

namespace App\Http\Controllers;

use App\Models\Lessons;

class PageController extends Controller
{
    public static string $title;

    public function index($slug = 'home')
    {
        $view = 'pages.' . ($slug ?: 'home');

        if (!view()->exists($view)) {
            abort(404);
        }

        $title = ucfirst(str_replace('-', ' ', $slug ?: 'home'));

        $lessons = new Lessons();

        return view($view, compact('title', 'lessons'));
    }
}
