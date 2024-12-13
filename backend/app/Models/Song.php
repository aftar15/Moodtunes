<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'mood',
        'file_path',
        'cover_path'
    ];

    protected $appends = ['audio_url', 'cover_url'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getAudioUrlAttribute()
    {
        return $this->file_path ? asset($this->file_path) : null;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_path ? asset('storage/' . $this->cover_path) : null;
    }

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
