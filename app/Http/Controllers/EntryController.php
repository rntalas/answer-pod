<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Locale;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EntryController extends Controller
{
    public function create(Request $request)
    {
        $unitId = $request->query('unit');

        $unit = Unit::findOrFail($unitId);

        return view('entries.create', [
            'unit' => $unit,
            'locales' => Locale::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $unitId = $request->query('unit');
        $unit = Unit::findOrFail($unitId);

        $request->merge(['unit_id' => $unit->id]);

        $entry = $this->saveEntry($request);

        return redirect()->route('unit.show', [
            'id' => $unitId,
        ]);
    }

    public function show(Entry $entry)
    {
        return view('entries.view', [
            'entry' => $entry->load('translations', 'images'),
        ]);
    }

    public function edit(Entry $entry, Request $request)
    {
        return view('entries.edit', [
            'entry' => $entry->load('translations', 'unit.subject', 'images'),
            'locales' => Locale::all(),
            'selectedLocale' => $request->query('locale', config('app.default_locale_id', 1)),
        ]);
    }

    public function update(Request $request, Entry $entry): RedirectResponse
    {
        $request->merge(['unit_id' => $entry->unit_id]);

        $this->saveEntry($request, $entry->id);

        return redirect()->route('entry.show', ['entry' => $entry->id]);
    }

    public function destroy(Entry $entry): RedirectResponse
    {
        $entry->images->each(function ($img) {
            Storage::disk('public')->delete($img->path);
        });

        $entry->delete();

        return redirect()->back();
    }

    protected function saveEntry(Request $request, $id = null): Entry
    {
        $validated = $this->validateEntry($request, $id);

        return DB::transaction(function () use ($validated, $request, $id) {

            $entry = $id
                ? Entry::findOrFail($id)
                : new Entry;

            $entry->fill([
                'number' => $validated['number'],
                'unit_id' => $validated['unit_id'],
                'statement' => $validated['statement'],
                'solution' => $validated['solution'],
            ])->save();

            $entry->translations()->updateOrCreate(
                ['locale_id' => $validated['locale_id']],
                [
                    'statement' => $validated['statement_text'] ?? null,
                    'solution' => $validated['solution_text'] ?? null,
                ]
            );

            $this->uploadImages($request, $entry);

            return $entry;
        });
    }

    protected function validateEntry(Request $request, $id = null): array
    {
        $validated = $request->validate([
            'number' => 'required|integer|between:1,500',
            'unit_id' => 'required|exists:units,id',
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
            'number.max' => __('entry.error.number_max'),
            'statement_text.required_if' => __('entry.error.statement'),
            'statement_image.required_if' => __('entry.error.statement'),
            'solution_text.required_if' => __('entry.error.solution'),
            'solution_image.required_if' => __('entry.error.solution'),
        ]);

        $exists = Entry::where('unit_id', $validated['unit_id'])
            ->where('number', $validated['number'])
            ->when($id, fn ($q) => $q->where('id', '!=', $id))
            ->exists();

        if ($exists) {
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

                $entry->images()
                    ->where('field', $field)
                    ->each(function ($img) {
                        Storage::disk('public')->delete($img->path);
                        $img->delete();
                    });

                foreach ($request->file("{$field}_image") as $index => $file) {
                    $entry->images()->create([
                        'field' => $field,
                        'path' => $file->store("{$field}s", 'public'),
                        'position' => $index + 1,
                    ]);
                }
            }
        }
    }
}
