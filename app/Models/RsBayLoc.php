<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsBayLoc extends Model
{
    //
    protected $table = 'rsbayloc';
    public $timestamps = false;

    protected $fillable = [
        'rssite',
        'rsbaynum',
        'createdate',
        'createdby',
    ];
}
