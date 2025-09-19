<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsLocation extends Model
{
    protected $table = 'rslocation';

    protected $primaryKey = 'id'; // Change if your PK is different

    public $timestamps = false; // Set to true if you have created_at/updated_at

    protected $fillable = [
        'rssite',
        'rswhse',
        'rsbaynum',
        'rsloc',
        'rsdec',
        'qty',
        'createdate',
        'createdby'
    ];
}
