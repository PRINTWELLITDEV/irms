<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RsUser extends Authenticatable
{
    use HasFactory, Notifiable;

    // Use the SQL Server connection if needed
    protected $connection = 'sqlsrv';

    // Set the exact schema + table name found in SQL Server (adjust if different)
    protected $table = 'dbo.rsusers'; // change to 'dbo.rs_users' or actual name if required

    // Primary key config (adjust if not 'userid')
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string';

    // If the table has no timestamps
    public $timestamps = false;

    // Mass-assignable attributes - adjust to your columns
    protected $fillable = [
        'userid', 'name', 'email', 'profile_pic_path', 'rssite', // ...
    ];

    // Convenience accessor for public URL of profile pic
    public function getProfilePicUrlAttribute()
    {
        if ($this->profile_pic_path) {
            return Storage::disk('public')->url($this->profile_pic_path);
        }
        return asset('uploads/user-profile/noprofile.png');
    }

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
        $keyName = $this->getKeyName();

        if (is_array($keyName)) {
            foreach ($keyName as $keyField) {
                $query->where($keyField, '=', $this->getAttribute($keyField));
            }
            return $query;
        }

        // single primary key
        return $query->where($keyName, '=', $this->getAttribute($keyName));
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
