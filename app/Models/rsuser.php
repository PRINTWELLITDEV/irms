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

/**
 * Checks whether the user has permission to access Quantity Move.
 * Author: Jim Dominic Pabalate
 * Date Created: September 17, 2026
 */
    public function hasQuantityMoveAccess()
{
    // Superadmin always has access
    if ($this->level == 1) {
        return true;
    }

    $file = storage_path('app/navigation_permissions.json');

    if (!file_exists($file)) {
        return false;
    }

    $permissions = json_decode(
        file_get_contents($file),
        true
    ) ?? [];

    return in_array(
        $this->userid,
        $permissions['quantity_move'] ?? []
    );
}


/**
 * Checks whether the user has permission to access Item Inquiry.
 * Author: Jim Dominic Pabalate
 * Date Created: September 25, 2026
 */
public function hasItemInquiryAccess()
{
    // Superadmin always has access
    if ($this->level == 1) {
        return true;
    }

    $file = storage_path('app/navigation_permissions.json');

    if (!file_exists($file)) {
        return false;
    }

    $permissions = json_decode(
        file_get_contents($file),
        true
    ) ?? [];

    return in_array(
        $this->userid,
        $permissions['item_inquiry'] ?? []
    );
}
}
