<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subjects')->where(fn ($query) => $query->where('locale_id', $request->input('locale_id'))),
            ],
            'units' => 'required|integer|min:1|max:100',
            'locale_id' => 'required|exists:locales,id',
        ], [
            'required' => __('subject.error.name.required'),
            'unique' => __('subject.error.name.exists'),
        ]);

        $validated['units'] = $validated['units'] > 0 ? (int) $validated['units'] : 1;

        Subject::query()->create($validated);

        return redirect()->route('page.show', ['slug' => '']);
    }

    public function edit($id)
    {
        $subject = Subject::query()->findOrFail($id);

        return view('subjects.edit', compact('subject'));
    }

    public function show($id)
    {
        $subject = Subject::query()->findOrFail($id);

        return view('subjects.view', compact('subject'));
    }

    public function destroy($id): RedirectResponse
    {
        $subject = Subject::query()->findOrFail($id);
        $subject?->delete();

        return redirect()
            ->route('page.show', ['slug' => '']);
    }
}
