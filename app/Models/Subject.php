<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Resource
{
    protected $fillable = ['units'];

    public function translations(): HasMany
    {
        return $this->hasMany(SubjectTranslation::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}