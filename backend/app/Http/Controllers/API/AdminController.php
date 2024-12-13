namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Song;
use App\Models\Playlist;
use App\Models\Favorite;
use App\Models\UserLog;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function getUserLogs()
    {
        $logs = UserLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($logs);
    }
    
    public function getMusicList(Request $request)
    {
        try {
            // Get existing songs from database
            $query = Song::query();

            if ($request->has('mood') && $request->mood !== 'All Moods') {
                $query->where('mood', $request->mood);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('artist', 'like', "%{$search}%");
                });
            }

            // Get songs from database with pagination
            $songs = $query->orderBy('created_at', 'desc')->paginate(10);
            
            // Transform the response to include proper URLs
            $songs->getCollection()->transform(function ($song) {
                $song->audio_url = asset($song->file_path);
                $song->cover_url = $song->cover_path ? asset('storage/' . $song->cover_path) : null;
                return $song;
            });

            return response()->json([
                'current_page' => $songs->currentPage(),
                'data' => $songs->items(),
                'first_page_url' => $songs->url(1),
                'from' => $songs->firstItem(),
                'last_page' => $songs->lastPage(),
                'last_page_url' => $songs->url($songs->lastPage()),
                'next_page_url' => $songs->nextPageUrl(),
                'path' => $songs->path(),
                'per_page' => $songs->perPage(),
                'prev_page_url' => $songs->previousPageUrl(),
                'to' => $songs->lastItem(),
                'total' => $songs->total(),
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getMusicList: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function getAvailableAudioFiles()
    {
        $audioPath = public_path('home/audio');
        $files = [];
        
        if (is_dir($audioPath)) {
            $audioFiles = array_diff(scandir($audioPath), ['.', '..']);
            foreach ($audioFiles as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg'])) {
                    $files[] = [
                        'name' => $file,
                        'path' => 'home/audio/' . $file
                    ];
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }
    
    public function addMusic(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'mood' => 'required|string',
            'audioPath' => 'required|string',
            'cover' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $coverPath = null;
            if ($request->hasFile('cover')) {
                $coverPath = $request->file('cover')->store('covers', 'public');
            }

            // Create song record
            $song = Song::create([
                'title' => $request->title,
                'artist' => $request->artist,
                'mood' => $request->mood,
                'file_path' => $request->audioPath,
                'cover_path' => $coverPath
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Music added successfully',
                'data' => $song
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding music: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding music: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateMusic(Request $request, $id)
    {
        $song = Song::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'string|max:255',
            'artist' => 'string|max:255',
            'mood_id' => 'exists:moods,id',
            'file_path' => 'string'
        ]);
        
        $song->update($validated);
        return response()->json($song);
    }
    
    public function deleteMusic($id)
    {
        $song = Song::findOrFail($id);
        $song->delete();
        return response()->json(null, 204);
    }
    
    public function getFavoritesList()
    {
        $favorites = Favorite::with(['user', 'song'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($favorites);
    }
    
    public function getPlaylistsList()
    {
        $playlists = Playlist::with(['user', 'songs'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return response()->json($playlists);
    }
    
    public function getDashboardStats()
    {
        $stats = [
            'total_songs' => Song::count(),
            'total_moods' => Mood::count(),
            'total_playlists' => Playlist::count(),
            'total_users' => User::count(),
            'total_favorites' => Favorite::count()
        ];
        
        return response()->json($stats);
    }
    
    public function getRecentActivities()
    {
        $activities = UserLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'description' => $log->description,
                    'datetime' => $log->created_at->format('M d, Y h:i A'),
                    'action' => 'View'
                ];
            });
            
        return response()->json($activities);
    }
}
