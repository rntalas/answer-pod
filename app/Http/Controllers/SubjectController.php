<?php

namespace App\Http\Controllers;

use App\Models\Locale;
use App\Models\Subject;
use App\Models\SubjectTranslation;
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
        $mainData = $this->validateMainData($request);
        $translationData = $this->validateTranslationData($request);

        $subject = Subject::create($mainData);
        $subject->translations()->create($translationData);

        return redirect()->route('page.show', ['slug' => '']);
    }

    public function show($id)
    {
        $subject = Subject::with('translations')->findOrFail($id);

        return view('subjects.view', compact('subject'));
    }

    public function edit($id, Request $request)
    {
        $subject = Subject::with('translations')->findOrFail($id);
        $locales = Locale::query()->get();
        $selectedLocale = $request->query('locale', 1);

        return view('subjects.edit', compact('subject', 'locales', 'selectedLocale'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $subject = Subject::findOrFail($id);
        $localeId = $request->input('locale_id');

        $mainData = $this->validateMainData($request, $id);
        $translationData = $this->validateTranslationData($request, $id);

        $subject->update($mainData);

        $translation = $subject->translations()->where('locale_id', $localeId)->first();

        if ($translation) {
            $translation->update($translationData);
        } else {
            $translationData['locale_id'] = $localeId;
            $subject->translations()->create($translationData);
        }

        return redirect()->route('subject.show', $id);
    }

    public function destroy($id): RedirectResponse
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function validateMainData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'units' => 'required|integer|min:1|max:100',
        ]);
    }

    protected function validateTranslationData(Request $request, ?int $id = null): array
    {
        $localeId = $request->input('locale_id');

        $ignoreId = null;
        if ($id) {
            $translation = SubjectTranslation::query()
                ->where('subject_id', $id)
                ->where('locale_id', $localeId)
                ->first();
            $ignoreId = $translation?->id;
        }

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subject_translations')
                    ->where(fn ($query) => $query->where('locale_id', $localeId))
                    ->ignore($ignoreId),
            ],
            'description' => [
                'nullable',
                'string',
                'max:255',
            ],
            'locale_id' => 'required|exists:locales,id',
        ], [
            'name.required' => __('subject.error.name.required'),
            'name.unique' => __('subject.error.name.exists'),
        ]);
    }
}
