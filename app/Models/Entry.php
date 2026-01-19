<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $fillable = ['img', 'sol', 'lid'];

    public function lesson() {
        return $this->belongsTo(Lesson::class, 'lid');
    }
}