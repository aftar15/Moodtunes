<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mood;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MoodController extends Controller
{
    public function index()
    {
        return Mood::with('songs')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'icon' => 'nullable|image'
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('mood-icons', 'public');
        }

        $mood = Mood::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $iconPath
        ]);

        return response()->json($mood, 201);
    }

    public function show(Mood $mood)
    {
        return $mood->load('songs');
    }

    public function update(Request $request, Mood $mood)
    {
        $request->validate([
            'name' => 'string',
            'description' => 'nullable|string',
            'icon' => 'nullable|image'
        ]);

        if ($request->hasFile('icon')) {
            if ($mood->icon) {
                Storage::disk('public')->delete($mood->icon);
            }
            $iconPath = $request->file('icon')->store('mood-icons', 'public');
            $mood->icon = $iconPath;
        }

        $mood->update($request->only(['name', 'description']));
        return response()->json($mood);
    }

    public function destroy(Mood $mood)
    {
        if ($mood->icon) {
            Storage::disk('public')->delete($mood->icon);
        }
        $mood->delete();
        return response()->json(null, 204);
    }
}
