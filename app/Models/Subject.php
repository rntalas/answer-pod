<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Resource
{
    protected $fillable = ['units',
        'locale_id',
        'name',
        'description',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(SubjectTranslation::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
