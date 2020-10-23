<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    public function inviter() {
    	return $this->belongsTo(User::class, 'invited_by');
    }

    public function team() {
    	return $this->belongsTo(Team::class);
    }
}
