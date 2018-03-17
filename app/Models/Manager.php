<?php

namespace App\Models;
use App\Http\Controllers\DogovorsController;


/**
 * App\Models\Client
 *
 * @property int $US_KEY
 * @property string $US_NAME
 * @property string $US_FNAME
 * @property string $US_MAILBOX
 * @property string $US_FullNameLat
 *
 * @property Dogovor $dogovor
 */

class Manager extends BaseModel
{
    protected $primaryKey = 'US_KEY';
    protected $table = 'UserList';

    /**
     * Получить догвора
     */
    public function dogovors()
    {
        return $this->hasMany(Dogovor::class, 'DG_OWNER', 'US_KEY');
    }
/*
    protected $appends = ['id', 'name'];
    public function getIdAttribute(){
        return $this->attributes['CT_KEY'];
    }

    public function getNameAttribute(){
        return $this->attributes['CT_NAME'];
    }
*/
}
