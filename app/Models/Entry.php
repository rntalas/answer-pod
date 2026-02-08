<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Resource
{
    protected $fillable = [
        'number',
        'unit_id',
        'statement',
        'solution',
        'position',
        'path',
        'field',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(EntryTranslation::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(EntryImage::class);
    }

    public function imagesByField(string $field): HasMany
    {
        return $this->hasMany(EntryImage::class)->where('field', $field);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
