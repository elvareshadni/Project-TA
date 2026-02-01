<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins'; // atau nama tabelmu

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'foto_profile',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
