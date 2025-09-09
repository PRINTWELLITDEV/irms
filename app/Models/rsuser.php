<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'rsusers';
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string'; 
    public $timestamps = false;

    protected $dates = ['create_date'];
    protected $hidden = ['password'];
}
