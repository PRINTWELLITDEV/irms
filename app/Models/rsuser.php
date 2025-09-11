<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class RsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'rsusers';

    /**
     * Composite primary key: rssite + userid
     */
    protected $primaryKey = ['rssite', 'userid'];
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $dates = ['create_date', 'updated_date'];
    protected $hidden = ['password'];

    protected $fillable = [
        'rssite',
        'userid',
        'name',
        'email',
        'password',
        'level',
        'user_type',
        'gender',
        'profile_pic_url',
        'updated_by',
        'updated_by_sql',
    ];

    /**
     * Ensure updating sets updated_by (Laravel authenticated user).
     */
    protected static function booted()
    {
        static::updating(function ($user) {
            if (Auth::check()) {
                $user->updated_by = Auth::user()->userid;
            }
        });
    }

    /**
     * Override setKeysForSaveQuery to handle composite PK.
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $keyField) {
            $query->where($keyField, '=', $this->getAttribute($keyField));
        }
        return $query;
    }

    /**
     * Override getKeyName for composite PK support.
     */
    public function getKeyName()
    {
        return $this->primaryKey;
    }

    /**
     * 🔑 Tell Laravel Auth which column is the identifier
     * Even though DB PK is composite, for login we'll use userid
     */
    public function getAuthIdentifierName()
    {
        return 'userid';
    }

    public function getAuthIdentifier()
    {
        return $this->userid;
    }

    /**
     * Example relationship: sessions belonging to this user.
     */
    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id', 'userid')
                    ->whereColumn('sessions.rssite', 'rsusers.rssite');
    }
}
