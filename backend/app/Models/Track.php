<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
	use HasFactory;

	protected $fillable = [
		'title',
		'artist',
		'album',
		'duration',
		'file_url',
		'cover_image_url',
		'category_id'
	];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function playlists()
	{
		return $this->belongsToMany(Playlist::class);
	}

	public function favoritedBy()
	{
		return $this->belongsToMany(User::class, 'favorites');
	}
}