<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IrmsSite extends Model
{
    protected $table = 'irms_site';
    protected $primaryKey = 'id'; // change if different
    public $timestamps = false;

    protected $fillable = [
        'rssite',
        'rssite_desc',
        'address',
        'logo_pic_url',
        'site_link',
        'create_date',
    ];
}
