<?php

namespace App\Models;

use App\Models\Invitation;
use App\Models\Team;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfilePictureAttribute($value) {
        return 'https://png.pngtree.com/png-clipart/20190924/original/pngtree-business-people-avatar-icon-user-profile-free-vector-png-image_4815126.jpg';
    }

    public function teams() {
        return $this->hasMany(Team::class);
    }

    public function invitations() {
        return $this->hasMany(Invitation::class);
    }

    public function allTeams() {
        return Team::where('user_id', $this->id)
            ->orWhereIn('id', TeamMember::where('user_id', $this->id)->pluck('team_id')->toArray());
    }
}
