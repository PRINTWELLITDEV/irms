<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsItemLoc extends Model
{
 protected $table = 'rsitemloc';

    protected $primaryKey = 'id'; // Change if your PK is different

    public $timestamps = false; // Set to true if you have created_at/updated_at

    protected $fillable = [
        'rssite',
        'rswhse',
        'rsbaynum',
        'rsloc',
        'rspallet_num',
        'job',
        'item',
        'desc',
        'um',
        'qty',
        'datercvd',
        'createdate',
        'createdby',
    ];

}
