<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Locale;
use App\Models\Subject;
use App\Models\SubjectTranslation;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $this->saveSubject($request);

        return redirect()->route('page.show', ['slug' => '']);
    }

    public function show($id)
    {
        return view('subjects.view', [
            'subject' => Subject::with('translations')->findOrFail($id),
            'units' => Unit::where('subject_id', $id)->get(),
            'locales' => Locale::all(),
        ]);
    }

    public function edit($id, Request $request)
    {
        $selectedLocale = $request->query('locale', config('app.default_locale_id', 1));
        $subject = Subject::with('translations')->findOrFail($id);

        $translation = $subject->translations->firstWhere('locale_id', $selectedLocale);
        $locale = Locale::findOrFail($selectedLocale);

        return view('subjects.edit', [
            'subject' => $subject,
            'selectedLocale' => $selectedLocale,
            'locale' => $locale,
            'translation' => $translation,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->saveSubject($request, $id);

        return redirect()->route('subject.show', ['subject' => $id]);
    }

    public function destroy($id): RedirectResponse
    {
        Subject::findOrFail($id)->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function saveSubject(Request $request, $id = null): void
    {
        $validated = $this->validate($request, $id);

        DB::transaction(function () use ($validated, $id) {
            $subject = $id ? Subject::findOrFail($id) : new Subject;

            $subject->fill(['units' => $validated['units']])->save();

            $subject->translations()->updateOrCreate(
                ['locale_id' => $validated['locale_id']],
                [
                    'name' => $validated['name'],
                    'description' => $validated['description'] ?? null,
                ]
            );

            $currentUnitCount = $subject->units()->count();

            if ($validated['units'] > $currentUnitCount) {
                for ($i = $currentUnitCount + 1; $i <= $validated['units']; $i++) {
                    Unit::create([
                        'subject_id' => $subject->id,
                        'number' => $i,
                    ]);
                }
            }

            if ($validated['units'] < $currentUnitCount) {
                Unit::where('subject_id', $subject->id)
                    ->where('number', '>', $validated['units'])
                    ->delete();
            }
        });
    }

    protected function validate(Request $request, ?int $subjectId = null): array
    {
        $localeId = $request->input('locale_id');
        $ignoreId = $subjectId
            ? SubjectTranslation::where('subject_id', $subjectId)
                ->where('locale_id', $localeId)
                ->value('id')
            : null;

        $validated = $request->validate([
            'units' => 'required|integer|min:1|max:25',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subject_translations')
                    ->where(fn ($q) => $q->where('locale_id', $localeId))
                    ->ignore($ignoreId),
            ],
            'description' => 'nullable|string|max:255',
            'locale_id' => 'required|exists:locales,id',
        ], [
            'name.required' => __('subject.error.name.required'),
            'name.unique' => __('subject.error.name.exists'),
        ]);

        if ($subjectId) {
            $maxUnit = Entry::whereHas('unit', fn ($q) => $q->where('subject_id', $subjectId))
                ->join('units', 'entries.unit_id', '=', 'units.id')
                ->max('units.number');

            if ($maxUnit && $validated['units'] < $maxUnit) {
                throw ValidationException::withMessages([
                    'units' => __('subject.error.units', ['unit' => $maxUnit]),
                ]);
            }
        }

        return $validated;
    }
}
