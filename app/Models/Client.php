<?php

namespace App\Models;
use App\Http\Controllers\DogovorsController;
use Carbon\Carbon;


/**
 * App\Models\Client
 *
 * @property INT $CL_KEY
 * @property string $CL_NAMERUS
 * @property string $CL_NAMELAT
 * @property string $CL_FNAMERUS
 * @property string $CL_FNAMELAT
 * @property string $cl_mail
 * @property string $CL_ISONLINE
 * @property \Carbon\Carbon $CL_BIRTHDAY
 * @property string $CL_ADDRESS
 * @property string $CL_PHONE
 * @property string $CL_PASPORTNUM
 * @property string $CL_PASPORTDATEEND
 * @property int $CL_TYPE
 * @property \Carbon\Carbon $CL_DATEUPDATE
 * @property Dogovor $dogovor
 */

class Client extends BaseModel
{
    protected $primaryKey = 'CL_KEY';
    protected $table = 'Clients';
    protected $dates = ['CL_DATEUPDATE', 'CL_BIRTHDAY', 'CL_PASPORTDATEEND'];
    protected $fillable = ['cl_mail', 'CL_ISONLINE'];
    const UPDATED_AT = 'CL_DATEUPDATE';

    /**
     * Получить соответвующего пользователя
     */
    public function user()
    {
        return $this->hasOne(\App\User::class, 'email', 'cl_mail' );
    }

    /**
     * Получить догвора
     */
    public function dogovors()
    {
        return $this->hasMany(Dogovor::class, 'DG_CLIENTKEY', 'CL_KEY');
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
