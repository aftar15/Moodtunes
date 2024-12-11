<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PlaybackController extends Controller
{
    /**
     * Start playback of a track
     */
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'track_id' => 'required|exists:tracks,id',
            'position' => 'nullable|integer|min:0',
            'volume' => 'nullable|integer|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $track = Track::findOrFail($request->track_id);
        
        // Store playback state in cache
        $playbackState = [
            'track_id' => $track->id,
            'position' => $request->position ?? 0,
            'volume' => $request->volume ?? 100,
            'is_playing' => true,
            'loop_mode' => 'none', // none, single, all
            'shuffle' => false
        ];
        
        Cache::put("playback_state_{$request->user()->id}", $playbackState, now()->addHours(1));

        return response()->json($playbackState);
    }

    /**
     * Update playback settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'position' => 'nullable|integer|min:0',
            'volume' => 'nullable|integer|min:0|max:100',
            'is_playing' => 'nullable|boolean',
            'loop_mode' => 'nullable|in:none,single,all',
            'shuffle' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cacheKey = "playback_state_{$request->user()->id}";
        $playbackState = Cache::get($cacheKey, []);
        
        if (empty($playbackState)) {
            return response()->json([
                'message' => 'No active playback session'
            ], 404);
        }

        // Update playback state with new values
        foreach ($request->all() as $key => $value) {
            if (isset($playbackState[$key])) {
                $playbackState[$key] = $value;
            }
        }

        Cache::put($cacheKey, $playbackState, now()->addHours(1));

        return response()->json($playbackState);
    }

    /**
     * Stop playback
     */
    public function stop(Request $request)
    {
        $cacheKey = "playback_state_{$request->user()->id}";
        Cache::forget($cacheKey);

        return response()->json(null, 204);
    }

    /**
     * Get current playback state
     */
    public function state(Request $request)
    {
        $playbackState = Cache::get("playback_state_{$request->user()->id}");
        
        if (!$playbackState) {
            return response()->json([
                'message' => 'No active playback session'
            ], 404);
        }

        return response()->json($playbackState);
    }
}
