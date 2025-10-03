<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RsGoodsReceiving extends Model
{
    protected $connection = 'sqlsrv';
    protected $table = 'rsgoodsreceiving';

    // If you have a composite primary key, set it here (adjust as needed)
    // protected $primaryKey = ['rssite', 'rswhse', 'rsbaynum', 'rsloc', 'rslot', 'rspallet_num', 'job', 'item'];
    // public $incrementing = false;
    // protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'rssite', 'rswhse', 'rsbaynum', 'rsloc', 'rslot', 'rspallet_num',
        'job', 'item', 'desc', 'um', 'qty', 'datercvd', 'docnum', 'createdby', 'createdate'
    ];

    // If you use composite PK, uncomment and adjust the following:
    // protected function setKeysForSaveQuery($query)
    // {
    //     $keyName = $this->getKeyName();
    //     if (is_array($keyName)) {
    //         foreach ($keyName as $keyField) {
    //             $query->where($keyField, '=', $this->getAttribute($keyField));
    //         }
    //         return $query;
    //     }
    //     return $query->where($keyName, '=', $this->getAttribute($keyName));
    // }

    // public function getKeyName()
    // {
    //     return $this->primaryKey;
    // }
}
