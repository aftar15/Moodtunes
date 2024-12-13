<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Song;
use App\Models\Playlist;
use App\Models\Favorite;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getProfile()
    {
        $user = Auth::user();
        return response()->json($user);
    }
    
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'string|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048'
        ]);
        
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        if ($request->hasFile('avatar')) {
            // Store avatar and get path
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }
        
        $user->update($validated);
        return response()->json($user);
    }
    
    public function getUserStats()
    {
        $user = Auth::user();
        
        $stats = [
            'total_favorites' => $user->favorites()->count(),
            'total_playlists' => $user->playlists()->count(),
            'total_songs_in_playlists' => $user->playlists()->withCount('songs')->get()->sum('songs_count'),
            'most_played_mood' => $user->favorites()->with('song.mood')
                ->get()
                ->groupBy('song.mood.name')
                ->map->count()
                ->sortDesc()
                ->keys()
                ->first() ?? 'No mood yet'
        ];
        
        return response()->json($stats);
    }
    
    public function getRecentActivity()
    {
        $user = Auth::user();
        
        $activities = UserLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'description' => $log->description,
                    'datetime' => $log->created_at->format('M d, Y h:i A')
                ];
            });
            
        return response()->json($activities);
    }
    
    public function getFavorites()
    {
        $user = Auth::user();
        
        $favorites = $user->favorites()
            ->with(['song.mood'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($favorites);
    }
    
    public function addToFavorites(Song $song)
    {
        $user = Auth::user();
        
        if (!$user->favorites()->where('song_id', $song->id)->exists()) {
            $favorite = $user->favorites()->create(['song_id' => $song->id]);
            
            // Log activity
            UserLog::create([
                'user_id' => $user->id,
                'description' => "Added {$song->title} to favorites"
            ]);
            
            return response()->json($favorite, 201);
        }
        
        return response()->json(['message' => 'Song already in favorites'], 409);
    }
    
    public function removeFromFavorites(Song $song)
    {
        $user = Auth::user();
        
        $user->favorites()->where('song_id', $song->id)->delete();
        
        // Log activity
        UserLog::create([
            'user_id' => $user->id,
            'description' => "Removed {$song->title} from favorites"
        ]);
        
        return response()->json(null, 204);
    }
    
    public function getPlaylists()
    {
        $user = Auth::user();
        
        $playlists = $user->playlists()
            ->withCount('songs')
            ->with(['songs.mood'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($playlists);
    }
    
    public function createPlaylist(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $playlist = $user->playlists()->create($validated);
        
        // Log activity
        UserLog::create([
            'user_id' => $user->id,
            'description' => "Created playlist {$playlist->name}"
        ]);
        
        return response()->json($playlist, 201);
    }
    
    public function updatePlaylist(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);
        
        $validated = $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string'
        ]);
        
        $playlist->update($validated);
        
        // Log activity
        UserLog::create([
            'user_id' => Auth::id(),
            'description' => "Updated playlist {$playlist->name}"
        ]);
        
        return response()->json($playlist);
    }
    
    public function deletePlaylist(Playlist $playlist)
    {
        $this->authorize('delete', $playlist);
        
        $playlistName = $playlist->name;
        $playlist->delete();
        
        // Log activity
        UserLog::create([
            'user_id' => Auth::id(),
            'description' => "Deleted playlist {$playlistName}"
        ]);
        
        return response()->json(null, 204);
    }
    
    public function addSongToPlaylist(Request $request, Playlist $playlist)
    {
        $this->authorize('update', $playlist);
        
        $validated = $request->validate([
            'song_id' => 'required|exists:songs,id'
        ]);
        
        if (!$playlist->songs()->where('song_id', $validated['song_id'])->exists()) {
            $playlist->songs()->attach($validated['song_id']);
            
            $song = Song::find($validated['song_id']);
            
            // Log activity
            UserLog::create([
                'user_id' => Auth::id(),
                'description' => "Added {$song->title} to playlist {$playlist->name}"
            ]);
            
            return response()->json(null, 201);
        }
        
        return response()->json(['message' => 'Song already in playlist'], 409);
    }
    
    public function removeSongFromPlaylist(Playlist $playlist, Song $song)
    {
        $this->authorize('update', $playlist);
        
        $playlist->songs()->detach($song->id);
        
        // Log activity
        UserLog::create([
            'user_id' => Auth::id(),
            'description' => "Removed {$song->title} from playlist {$playlist->name}"
        ]);
        
        return response()->json(null, 204);
    }
}
