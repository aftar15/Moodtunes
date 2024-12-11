<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
	public function index()
	{
		$categories = Category::all();
		return response()->json($categories);
	}

	public function show(Category $category)
	{
		return response()->json($category);
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'mood_type' => 'required|string|max:50',
			'image_url' => 'nullable|url'
		]);

		if ($validator->fails()) {
			return response()->json([
				'message' => 'Validation failed',
				'errors' => $validator->errors()
			], 422);
		}

		$category = Category::create($request->all());
		return response()->json($category, 201);
	}

	public function update(Request $request, Category $category)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'sometimes|required|string|max:255',