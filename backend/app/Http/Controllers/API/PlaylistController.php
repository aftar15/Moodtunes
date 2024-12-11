<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->playlists()->with('songs')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image',
            'song_ids' => 'array'
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('playlist-covers', 'public');
        }

        $playlist = $request->user()->playlists()->create([
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $coverPath
        ]);

        if ($request->has('song_ids')) {
            $order = 0;
            $songIds = collect($request->song_ids)->mapWithKeys(function ($id) use (&$order) {
                return [$id => ['order' => $order++]];
            });
            $playlist->songs()->sync($songIds);
        }

        return response()->json($playlist->load('songs'), 201);
    }

    public function show(Playlist $playlist)
    {
        $this->authorize('view', $playlist);
        return $playlist->load('songs');
    }

    public function update(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);

        $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image',
            'song_ids' => 'array'
        ]);

        if ($request->hasFile('cover_image')) {
            if ($playlist->cover_image) {
                Storage::disk('public')->delete($playlist->cover_image);
            }
            $coverPath = $request->file('cover_image')->store('playlist-covers', 'public');
            $playlist->cover_image = $coverPath;
        }

        $playlist->update($request->only(['name', 'description']));

        if ($request->has('song_ids')) {
            $order = 0;
            $songIds = collect($request->song_ids)->mapWithKeys(function ($id) use (&$order) {
                return [$id => ['order' => $order++]];
            });
            $playlist->songs()->sync($songIds);
        }

        return response()->json($playlist->load('songs'));
    }

    public function destroy(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);
        
        if ($playlist->cover_image) {
            Storage::disk('public')->delete($playlist->cover_image);
        }
        $playlist->delete();
        return response()->json(null, 204);
    }

    public function addSong(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);
        
        $request->validate([
            'song_id' => 'required|exists:songs,id'
        ]);

        $maxOrder = $playlist->songs()->max('order') ?? -1;
        $playlist->songs()->attach($request->song_id, ['order' => $maxOrder + 1]);

        return response()->json($playlist->load('songs'));
    }

    public function removeSong(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);
        
        $request->validate([
            'song_id' => 'required|exists:songs,id'
        ]);

        $playlist->songs()->detach($request->song_id);
        return response()->json($playlist->load('songs'));
    }
}
