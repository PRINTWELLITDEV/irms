<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsGoodsDispatching extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'rsgoodsdispatching';

    // If you have a composite primary key, set it here (adjust as needed)
    // protected $primaryKey = ['rssite', 'rswhse', 'rsloc', 'rslot', 'rspallet_num', 'job', 'item'];
    // public $incrementing = false;
    // protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'rssite', 'rswhse', 'rsloc', 'rslot', 'rspallet_num',
        'job', 'item', 'desc', 'um', 'qty', 'datedispatch', 'docnum', 'createdby', 'createdate'
    ];
}
