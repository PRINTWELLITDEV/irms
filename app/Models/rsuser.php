<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class RsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    // Use SQL Server connection
    protected $connection = 'sqlsrv';

    // Table name (no schema prefix needed for Laravel migrations)
    protected $table = 'rsusers';

    // Composite primary key
    protected $primaryKey = ['rssite', 'userid'];
    public $incrementing = false;
    protected $keyType = 'string';

    // No timestamps
    public $timestamps = false;

    // Fillable columns (match migration)
    protected $fillable = [
        'rssite', 'userid', 'name', 'password', 'email',
        'department', 'section', 'position', 'level',
        'create_date', 'updated_date', 'updated_by', 'updated_by_sql',
        'gender', 'profile_pic_url', 'remember_token'
    ];

    // Accessor for profile image
    public function getProfilePicUrlAttribute()
    {
        if (!empty($this->attributes['profile_pic_url'])) {
            return asset($this->attributes['profile_pic_url']);
        }
        return asset('uploads/user-profile/noprofile.png');
    }

    // Composite PK save logic
    protected function setKeysForSaveQuery($query)
    {
        $keyName = $this->getKeyName();
        if (is_array($keyName)) {
            foreach ($keyName as $keyField) {
                $query->where($keyField, '=', $this->getAttribute($keyField));
            }
            return $query;
        }
        return $query->where($keyName, '=', $this->getAttribute($keyName));
    }

    public function getKeyName()
    {
        return $this->primaryKey;
    }

    // Auth identifier for Laravel
    public function getAuthIdentifierName()
    {
        return 'userid';
    }

    public function getAuthIdentifier()
    {
        return $this->userid;
    }

    // Optional: update 'updated_by' on update
    protected static function booted()
    {
        static::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::user()->userid;
            }
        });
    }
}
