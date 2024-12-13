<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\MoodController;
use App\Http\Controllers\API\PlaybackController;
use App\Http\Controllers\API\PlaylistController;
use App\Http\Controllers\API\SongController;
use App\Http\Controllers\API\TrackController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'admin']], function () {
    // User management
    Route::get('users/logs', [AdminController::class, 'getUserLogs']);
    
    // Music management
    Route::get('music', [AdminController::class, 'getMusicList']);
    Route::get('music/available-audio', [AdminController::class, 'getAvailableAudioFiles']);
    Route::post('music', [AdminController::class, 'addMusic']);
    Route::put('music/{id}', [AdminController::class, 'updateMusic']);
    Route::delete('music/{id}', [AdminController::class, 'deleteMusic']);
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    
    // Categories
    Route::apiResource('categories', CategoryController::class);
    
    // Tracks
    Route::apiResource('tracks', TrackController::class);
    Route::post('tracks/upload', [TrackController::class, 'upload']);
    Route::get('tracks/category/{categoryId}', [TrackController::class, 'byCategory']);
    
    // Playback
    Route::post('playback/start', [PlaybackController::class, 'start']);
    Route::put('playback', [PlaybackController::class, 'update']);
    Route::delete('playback', [PlaybackController::class, 'stop']);
    Route::get('playback/state', [PlaybackController::class, 'state']);

    // Songs
    Route::get('songs', [SongController::class, 'index']);
    Route::post('songs', [SongController::class, 'store']);
    Route::get('songs/{song}', [SongController::class, 'show']);
    Route::post('songs/{song}', [SongController::class, 'update']);
    Route::delete('songs/{song}', [SongController::class, 'destroy']);
    Route::get('songs/mood/{moodId}', [SongController::class, 'byMood']);

    // Playlists
    Route::apiResource('playlists', PlaylistController::class);
    Route::post('playlists/{playlist}/songs', [PlaylistController::class, 'addSong']);
    Route::delete('playlists/{playlist}/songs/{song}', [PlaylistController::class, 'removeSong']);

    // Moods
    Route::get('moods', [MoodController::class, 'index']);
    Route::post('moods', [MoodController::class, 'store']);
    Route::get('moods/{mood}', [MoodController::class, 'show']);
    Route::put('moods/{mood}', [MoodController::class, 'update']);
    Route::delete('moods/{mood}', [MoodController::class, 'destroy']);

    // User Dashboard Routes
    Route::prefix('user')->group(function () {
        // User Profile
        Route::get('/profile', [UserController::class, 'getProfile']);
        Route::put('/profile', [UserController::class, 'updateProfile']);
        
        // User Stats
        Route::get('/stats', [UserController::class, 'getUserStats']);
        
        // Recent Activity
        Route::get('/activity', [UserController::class, 'getRecentActivity']);
        
        // Favorite Songs
        Route::get('/favorites', [UserController::class, 'getFavorites']);
        Route::post('/favorites/{song}', [UserController::class, 'addToFavorites']);
        Route::delete('/favorites/{song}', [UserController::class, 'removeFromFavorites']);
        
        // Playlists
        Route::get('/playlists', [UserController::class, 'getPlaylists']);
        Route::post('/playlists', [UserController::class, 'createPlaylist']);
        Route::put('/playlists/{playlist}', [UserController::class, 'updatePlaylist']);
        Route::delete('/playlists/{playlist}', [UserController::class, 'deletePlaylist']);
        Route::post('/playlists/{playlist}/songs', [UserController::class, 'addSongToPlaylist']);
        Route::delete('/playlists/{playlist}/songs/{song}', [UserController::class, 'removeSongFromPlaylist']);
    });
});
