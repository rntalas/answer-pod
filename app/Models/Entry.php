<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Resource
{
    protected $fillable = ['number', 'unit', 'subject_id'];

    public function translations(): HasMany
    {
        return $this->hasMany(EntryTranslation::class);
    }
}
