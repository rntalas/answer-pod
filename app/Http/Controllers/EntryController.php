<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\EntryTranslation;
use App\Models\Locale;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    protected function getSubjects(): Collection
    {
        return Subject::with(['translations' => function ($query) {
            $query->select('id', 'subject_id', 'name', 'locale_id')
                ->whereIn('locale_id', [Subject::getCurrentLocaleId(), config('app.default_locale_id', 1)]);
        }])->get(['id', 'units']);
    }

    public function create(Request $request)
    {
        $subjects = $this->getSubjects();

        return view('entries.create', compact('subjects', 'subjects'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mainData = $this->validateMainData($request);
        $translationData = $this->validateTranslationData($request);

        $entry = Entry::query()->create($mainData);
        $entry->translations()->create($translationData);

        return redirect()->route('entry.show', $entry->id);
    }

    public function show($id)
    {
        $entry = Entry::with('translations')->findOrFail($id);

        return view('entries.view', compact('entry'));
    }

    public function edit($id, Request $request)
    {
        $entry = Entry::with('translations', 'subject')->findOrFail($id);
        $subjects = $this->getSubjects();
        $locales = Locale::query()->get();
        $selectedLocale = $request->query('locale', 1);

        return view('entries.edit', compact('entry', 'subjects', 'locales', 'selectedLocale'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $entry = Entry::query()->findOrFail($id);
        $localeId = $request->input('locale_id');

        $mainData = $this->validateMainData($request, $id);
        $translationData = $this->validateTranslationData($request, $id);

        $entry?->update($mainData);

        $translation = $entry?->translations()->where('locale_id', $localeId)->first();

        if ($translation) {
            $translation->update($translationData);
        } else {
            $translationData['locale_id'] = $localeId;
            $entry?->translations()->create($translationData);
        }

        return redirect()->route('entry.show', $id);
    }

    public function destroy($id): RedirectResponse
    {
        $entry = Entry::query()->findOrFail($id);
        $entry?->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function validateMainData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'number' => 'required|integer|min:1|max:100',
            'subject_id' => 'required|integer|min:1',
            'unit' => 'required|integer|min:1|max:100',
        ], [
            'unit.required' => __('entry.error.subject'),
        ]);
    }

    protected function validateTranslationData(Request $request, ?int $id = null): array
    {
        $localeId = $request->input('locale_id');

        $ignoreId = null;
        if ($id) {
            $translation = EntryTranslation::query()
                ->where('entry_id', $id)
                ->where('locale_id', $localeId)
                ->first();
            $ignoreId = $translation?->id;
        }

        return $request->validate([
            'statement' => 'required|string',
            'solution' => 'required|string',
            'locale_id' => 'required|exists:locales,id',
        ], [
            'statement.required' => __('entry.error.statement'),
            'solution.required' => __('entry.error.solution'),
        ]);
    }
}
