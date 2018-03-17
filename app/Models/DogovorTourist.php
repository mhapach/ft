<?php

namespace App\Models;

/**
 * App\Models\DogovorTourist
 * @property int $TU_KEY
 * @property string $TU_NAMELAT
 * @property string $TU_FNAMELAT
 * @property string $TU_PASPORTNUM
 * @property int $TU_DGKEY
 * @property int $TU_BIRTHDAY
 *
 * @property Dogovor $dogovor
 *
 */

class DogovorTourist extends BaseModel
{
    protected $primaryKey = 'TU_KEY';
    protected $table = 'tbl_Turist';
    protected $dates = ['TU_BIRTHDAY'];

    /**
     * Получить договор
     */
    public function dogovor()
    {
        return $this->belongsTo(City::class, 'TU_DGKEY', 'DG_Key');
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
