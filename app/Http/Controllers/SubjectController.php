<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\SubjectTranslation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends ResourceController
{
    protected function modelClass(): string
    {
        return Subject::class;
    }

    protected function translationModelClass(): string
    {
        return SubjectTranslation::class;
    }

    protected function viewPrefix(): string
    {
        return 'subjects';
    }

    protected function routePrefix(): string
    {
        return 'subject';
    }

    protected function validatedData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'units' => 'required|integer|min:1|max:100',
        ]);
    }

    protected function validatedTranslationData(Request $request, ?int $id = null): array
    {
        $localeId = $request->input('locale_id');

        $ignoreId = null;
        if ($id) {
            $translation = SubjectTranslation::query()->where('subject_id', $id)
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
            'locale_id' => 'required|exists:locales,id',
        ], [
            'name.required' => __('subject.error.name.required'),
            'name.unique' => __('subject.error.name.exists'),
        ]);
    }
}
