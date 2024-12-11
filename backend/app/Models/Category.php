<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
		'description',
		'mood_type',
		'image_url'
	];

	public function tracks()
	{
		return $this->hasMany(Track::class);
	}
}