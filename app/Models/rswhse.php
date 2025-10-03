<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsWhse extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'rswhse';
    protected $primaryKey = 'rswhse';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'rssite',
        'rswhse',
        'name',
        'addr',
        'createdate',
        'createdby',
    ];

    // Optionally, set createdate automatically
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->createdate = now();
        });
    }

    public function site()
    {
        return $this->belongsTo(IrmsSite::class, 'rssite', 'rssite');
    }
}
