<?php

namespace App\Http\Controllers;

use App\Models\Entries;
use App\Models\Lessons;
use App\Models\Subjects;

class PageController extends Controller
{
    public static string $title;

    public function index($slug = 'home')
    {
        $view = 'pages.'.($slug ?: 'home');

        if (! view()->exists($view)) {
            abort(404);
        }

        $title = ucfirst(str_replace('-', ' ', $slug ?: 'home'));

        $lessons = new Lessons;
        $subjects = new Subjects;
        $entries = new Entries;

        return view($view, compact('title', 'lessons', 'subjects', 'entries'));
    }
}
