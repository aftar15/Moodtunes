<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TrackController extends Controller
{
    /**
     * Display a listing of the tracks.
     */
    public function index()
    {
        $tracks = Track::with(['category'])->get();
        return response()->json($tracks);
    }

    /**
     * Store a newly created track in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'duration' => 'required|integer',
            'file_url' => 'required|url',
            'cover_image_url' => 'nullable|url',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $track = Track::create($request->all());
        return response()->json($track, 201);
    }

    /**
     * Display the specified track.
     */
    public function show(Track $track)
    {
        return response()->json($track->load('category'));
    }

    /**
     * Update the specified track in storage.
     */
    public function update(Request $request, Track $track)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'artist' => 'sometimes|required|string|max:255',
            'album' => 'nullable|string|max:255',
            'duration' => 'sometimes|required|integer',
            'file_url' => 'sometimes|required|url',
            'cover_image_url' => 'nullable|url',
            'category_id' => 'sometimes|required|exists:categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $track->update($request->all());
        return response()->json($track);
    }

    /**
     * Remove the specified track from storage.
     */
    public function destroy(Track $track)
    {
        $track->delete();
        return response()->json(null, 204);
    }

    /**
     * Get tracks by category.
     */
    public function byCategory($categoryId)
    {
        $tracks = Track::where('category_id', $categoryId)->get();
        return response()->json($tracks);
    }

    /**
     * Upload a track file.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'track_file' => 'required|file|mimes:mp3,wav,ogg|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('track_file');
        $path = $file->store('tracks', 'public');

        return response()->json([
            'file_url' => Storage::url($path)
        ]);
    }
}
