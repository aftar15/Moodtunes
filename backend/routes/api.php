<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MoodController;
use App\Http\Controllers\API\PlaybackController;
use App\Http\Controllers\API\PlaylistController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\TrackController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Tracks
    Route::apiResource('tracks', TrackController::class);
    Route::post('/tracks/upload', [TrackController::class, 'upload']);
    Route::get('/tracks/category/{categoryId}', [TrackController::class, 'byCategory']);
    
    // Playback
    Route::post('/playback/start', [PlaybackController::class, 'start']);
    Route::put('/playback', [PlaybackController::class, 'update']);
    Route::delete('/playback', [PlaybackController::class, 'stop']);
    Route::get('/playback/state', [PlaybackController::class, 'state']);

    // Songs
    Route::get('/songs', [SongController::class, 'index']);
    Route::post('/songs', [SongController::class, 'store']);
    Route::get('/songs/{song}', [SongController::class, 'show']);
    Route::post('/songs/{song}', [SongController::class, 'update']);
    Route::delete('/songs/{song}', [SongController::class, 'destroy']);
    Route::get('/songs/mood/{moodId}', [SongController::class, 'byMood']);

    // Playlists
    Route::get('/playlists', [PlaylistController::class, 'index']);
    Route::post('/playlists', [PlaylistController::class, 'store']);
    Route::get('/playlists/{playlist}', [PlaylistController::class, 'show']);
    Route::put('/playlists/{playlist}', [PlaylistController::class, 'update']);
    Route::delete('/playlists/{playlist}', [PlaylistController::class, 'destroy']);
    Route::post('/playlists/{playlist}/songs', [PlaylistController::class, 'addSong']);
    Route::delete('/playlists/{playlist}/songs', [PlaylistController::class, 'removeSong']);

    // Moods
    Route::get('/moods', [MoodController::class, 'index']);
    Route::post('/moods', [MoodController::class, 'store']);
    Route::get('/moods/{mood}', [MoodController::class, 'show']);
    Route::put('/moods/{mood}', [MoodController::class, 'update']);
    Route::delete('/moods/{mood}', [MoodController::class, 'destroy']);
});
