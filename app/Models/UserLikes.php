<?php

namespace App\Models;

use App\Events\UserLikedAMovieEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLikes extends Model
{
    use HasFactory;

    protected $dispatchesEvents = [
        'saved' => UserLikedAMovieEvent::class,
        'updated' => UserLikedAMovieEvent::class,
    ];
}
