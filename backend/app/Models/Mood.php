<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mood extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon'
    ];

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class);
    }
}
