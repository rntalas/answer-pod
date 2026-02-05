<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntryTranslation extends Model
{
    protected $fillable = ['entry_id', 'statement', 'solution', 'locale_id'];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }
}
