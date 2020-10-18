<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
    	'unogs_id',
		'netflix_id',
		'image',
		'poster',
		'vtype',
		'imdb_id',
		'title',
		'clist',
		'synopsis',
		'imdb_rating',
		'title_date',
		'average_rating',
		'release_year',
		'runtime',
    ];
}
