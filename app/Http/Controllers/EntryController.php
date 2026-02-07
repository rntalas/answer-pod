<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Locale;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EntryController extends Controller
{
    public function create()
    {
        return view('entries.create', ['subjects' => $this->getSubjects()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validate($request);

        $entry = Entry::query()->create([
            'number' => $validated['number'],
            'subject_id' => $validated['subject_id'],
            'unit' => $validated['unit'],
            'statement' => $validated['statement'],
            'solution' => $validated['solution'],
        ]);

        $entry->translations()->create([
            'locale_id' => $validated['locale_id'],
            'statement' => $validated['statement_text'] ?? null,
            'solution' => $validated['solution_text'] ?? null,
        ]);

        $this->uploadImages($request, $entry);

        return redirect()->route('entry.show', $entry->id);
    }

    public function show($id)
    {
        return view('entries.view', ['entry' => Entry::with('translations', 'images')->findOrFail($id)]);
    }

    public function edit($id, Request $request)
    {
        return view('entries.edit', [
            'entry' => Entry::with('translations', 'subject', 'images')->findOrFail($id),
            'subjects' => $this->getSubjects(),
            'locales' => Locale::all(),
            'selectedLocale' => $request->query('locale', config('app.default_locale_id', 1)),
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $this->validate($request);
        $entry = Entry::query()->findOrFail($id);

        $entry?->update([
            'number' => $validated['number'],
            'subject_id' => $validated['subject_id'],
            'unit' => $validated['unit'],
            'statement' => $request->boolean('statement'),
            'solution' => $request->boolean('solution'),
        ]);

        $entry?->translations()->updateOrCreate(
            ['locale_id' => $validated['locale_id']],
            ['statement' => $validated['statement_text'] ?? null, 'solution' => $validated['solution_text'] ?? null]
        );

        $this->uploadImages($request, $entry);

        return redirect()->route('entry.show', $id);
    }

    public function destroy($id): RedirectResponse
    {
        $entry = Entry::query()->findOrFail($id);

        $entry?->images->each(fn ($img) => Storage::disk('public')->delete($img->path));
        $entry?->delete();

        return redirect()->route('page.show', ['slug' => '']);
    }

    protected function validate(Request $request): array
    {
        $validated = $request->validate([
            'number' => 'required|integer|between:1,500',
            'subject_id' => 'required|integer|min:1',
            'unit' => 'required|integer|between:1,100',
            'locale_id' => 'required|exists:locales,id',
            'statement' => 'required|boolean',
            'solution' => 'required|boolean',
            'statement_text' => 'required_if:statement,0|nullable',
            'solution_text' => 'required_if:solution,0|nullable',
            'statement_image' => 'required_if:statement,1|nullable|array',
            'statement_image.*' => 'image|max:2048',
            'solution_image' => 'required_if:solution,1|nullable|array',
            'solution_image.*' => 'image|max:2048',
        ], [
            'subject_id.required' => __('entry.error.subject'),
            'number.max' => __('entry.error.number_max'),
            'statement_text.required_if' => __('entry.error.statement'),
            'statement_image.required_if' => __('entry.error.statement'),
            'solution_text.required_if' => __('entry.error.solution'),
            'solution_image.required_if' => __('entry.error.solution'),
        ]);

        $entry = Entry::query()->where('subject_id', $validated['subject_id'])
            ->where('unit', $validated['unit'])
            ->where('number', $validated['number'])
            ->exists();

        if ($entry) {
            throw ValidationException::withMessages([
                'unique' => __('entry.error.unique'),
            ]);
        }

        return $validated;
    }

    protected function uploadImages(Request $request, Entry $entry): void
    {
        foreach (['statement', 'solution'] as $field) {
            if ($request->hasFile("{$field}_image")) {
                $entry->images()->where('field', $field)->get()->each(fn ($img) => Storage::disk('public')->delete($img->path) && $img->delete());

                foreach ($request->file("{$field}_image") as $index => $file) {
                    $entry->images()->create(['field' => $field, 'path' => $file->store("{$field}s", 'public'), 'position' => $index + 1]);
                }
            }
        }
    }

    protected function getSubjects(): Collection
    {
        return Subject::with(['translations' => fn ($q) => $q->select('id', 'subject_id', 'name', 'locale_id')
            ->whereIn('locale_id', [Subject::getCurrentLocaleId(), config('app.default_locale_id', 1)])])->get(['id', 'units']);
    }
}
