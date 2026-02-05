<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectTranslation extends Model
{
    protected $fillable = ['subject_id', 'locale_id', 'name', 'description'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }
}
