<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\EntryTranslation;
use App\Models\Locale;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function create(Request $request)
    {
        $subjects = Subject::with('translations')->get();
        $subjectId = $request->query('subject_id');
        $subject = $subjectId ? Subject::findOrFail($subjectId) : null;

        return view('entries.create', compact('subjects', 'subject'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mainData = $this->validateMainData($request);
        $translationData = $this->validateTranslationData($request);

        $entry = Entry::create($mainData);
        $entry->translations()->create($translationData);

        return redirect()->route('entry.show', $entry->id);
    }

    public function show($id)
    {
        $entry = Entry::with('translations', 'subject')->findOrFail($id);
        $subject = $entry->subject;
        $subjects = Subject::with('translations')->get();

        return view('entries.view', compact('entry', 'subject', 'subjects'));
    }

    public function edit($id, Request $request)
    {
        $entry = Entry::with('translations', 'subject')->findOrFail($id);
        $subject = $entry->subject;
        $subjects = Subject::with('translations')->get();
        $locales = Locale::query()->get();
        $selectedLocale = $request->query('locale', 1);

        return view('entries.edit', compact('entry', 'subject', 'subjects', 'locales', 'selectedLocale'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $entry = Entry::findOrFail($id);
        $localeId = $request->input('locale_id');

        $mainData = $this->validateMainData($request, $id);
        $translationData = $this->validateTranslationData($request, $id);

        $entry->update($mainData);

        $translation = $entry->translations()->where('locale_id', $localeId)->first();

        if ($translation) {
            $translation->update($translationData);
        } else {
            $translationData['locale_id'] = $localeId;
            $entry->translations()->create($translationData);
        }

        return redirect()->route('entry.show', $id);
    }

    public function destroy($id): RedirectResponse
    {
        $entry = Entry::findOrFail($id);
        $entry->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function validateMainData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'number' => 'required|integer|min:1|max:100',
            'subject_id' => 'required|integer|min:1',
            'unit' => 'required|integer|min:1|max:100',
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
