<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;

class IndexController extends Controller
{
    public function index(): void {

        $name = 'sdsdf';

        $lesson = new Lesson();

        if ($lesson::all()->count() > 0) {
            echo 'lessons found';
        } else {
            echo 'no lesson found';
        }
    }
}