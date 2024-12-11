<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Song extends Model
{
    protected $fillable = [
        'title',
        'artist',
        'cover_image',
        'file_path',
        'duration'
    ];

    public function moods(): BelongsToMany
    {
        return $this->belongsToMany(Mood::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class)
            ->withPivot('order')
            ->orderBy('order');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
