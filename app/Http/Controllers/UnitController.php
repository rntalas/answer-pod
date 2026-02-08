<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Unit;

class UnitController extends Controller
{
    public function show($id)
    {
        $unit = Unit::with('subject.translations')->findOrFail($id);
        $subject = $unit->subject;
        $entries = Entry::where('unit_id', $id)->get();

        return view('units.view', compact('unit', 'subject', 'entries'));
    }
}
