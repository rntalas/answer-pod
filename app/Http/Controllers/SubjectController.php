<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Locale;
use App\Models\Subject;
use App\Models\SubjectTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubjectController extends Controller
{
    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request);

        $subject = Subject::create(['units' => $validated['units']]);
        $subject->translations()->create([
            'locale_id' => $validated['locale_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('page.show', ['slug' => '']);
    }

    public function show($id)
    {
        return view('subjects.view', ['subject' => Subject::with('translations')->findOrFail($id)]);
    }

    public function edit($id, Request $request)
    {
        return view('subjects.edit', [
            'subject' => Subject::with('translations')->findOrFail($id),
            'locales' => Locale::all(),
            'selectedLocale' => $request->query('locale', 1),
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $this->validate($request, $id);
        $subject = Subject::findOrFail($id);

        $subject->update(['units' => $validated['units']]);
        $subject->translations()->updateOrCreate(
            ['locale_id' => $validated['locale_id']],
            ['name' => $validated['name'], 'description' => $validated['description'] ?? null]
        );

        return redirect()->route('subject.show', $id);
    }

    public function destroy($id): RedirectResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->entries()->delete();
        $subject->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function validate(Request $request, ?int $subjectId = null): array
    {
        $localeId = $request->input('locale_id');
        $ignoreId = null;

        if ($subjectId) {
            $ignoreId = SubjectTranslation::where('subject_id', $subjectId)
                ->where('locale_id', $localeId)
                ->value('id');
        }

        $validated = $request->validate([
            'units' => 'required|integer|min:1|max:100',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subject_translations')->where(fn ($q) => $q->where('locale_id', $localeId))->ignore($ignoreId),
            ],
            'description' => 'nullable|string|max:255',
            'locale_id' => 'required|exists:locales,id',
        ], [
            'name.required' => __('subject.error.name.required'),
            'name.unique' => __('subject.error.name.exists'),
        ]);

        if ($subjectId) {
            $maxUnit = Entry::where('subject_id', $subjectId)->max('unit');

            if ($maxUnit && $validated['units'] < $maxUnit) {
                throw ValidationException::withMessages([
                    'units' => __('subject.error.units', ['unit' => $maxUnit]),
                ]);

                // dd(__('subject.error.units', ['unit' => $maxUnit]));
            }
        }

        return $validated;
    }
}
