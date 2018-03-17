<?php

namespace App\Models;
/**
 * App\Models\RoomCategory
 *
 * @property string $RC_KEY
 * @property string $RC_CODE
 * @property string $RC_NAME
 * @property string $RC_NAMELAT
 */
class RoomCategory extends BaseModel
{
    protected $table = 'RoomsCategory';
/*
    protected $appends = ['id', 'name', 'name_lat', 'code'];

    public function getIdAttribute(){
        return $this->attributes['RC_KEY'];
    }

    public function getCodeAttribute(){
        return $this->attributes['RC_CODE'];
    }

    public function getNameAttribute(){
        return $this->attributes['RC_NAME'];
    }

    public function getNameLatAttribute(){
        return $this->attributes['RC_NAMELAT'];
    }
*/
}
