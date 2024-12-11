<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    public function index()
    {
        return Song::with('moods')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'artist' => 'required|string',
            'audio_file' => 'required|file|mimes:mp3,wav',
            'cover_image' => 'nullable|image',
            'mood_ids' => 'array'
        ]);

        $audioPath = $request->file('audio_file')->store('songs', 'public');
        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('covers', 'public');
        }

        $song = Song::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'file_path' => $audioPath,
            'cover_image' => $coverPath,
        ]);

        if ($request->has('mood_ids')) {
            $song->moods()->sync($request->mood_ids);
        }

        return response()->json($song, 201);
    }

    public function show(Song $song)
    {
        return $song->load('moods');
    }

    public function update(Request $request, Song $song)
    {
        $request->validate([
            'title' => 'string',
            'artist' => 'string',
            'audio_file' => 'file|mimes:mp3,wav',
            'cover_image' => 'nullable|image',
            'mood_ids' => 'array'
        ]);

        if ($request->hasFile('audio_file')) {
            Storage::disk('public')->delete($song->file_path);
            $audioPath = $request->file('audio_file')->store('songs', 'public');
            $song->file_path = $audioPath;
        }

        if ($request->hasFile('cover_image')) {
            if ($song->cover_image) {
                Storage::disk('public')->delete($song->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('covers', 'public');
            $song->cover_image = $coverPath;
        }

        $song->update($request->only(['title', 'artist']));

        if ($request->has('mood_ids')) {
            $song->moods()->sync($request->mood_ids);
        }

        return response()->json($song);
    }

    public function destroy(Song $song)
    {
        Storage::disk('public')->delete($song->file_path);
        if ($song->cover_image) {
            Storage::disk('public')->delete($song->cover_image);
        }
        $song->delete();
        return response()->json(null, 204);
    }

    public function byMood($moodId)
    {
        return Song::whereHas('moods', function($query) use ($moodId) {
            $query->where('moods.id', $moodId);
        })->with('moods')->get();
    }
}
