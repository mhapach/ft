<?php

namespace App\Models;
/**
 * App\Models\Room
 *
 * @property string $RM_KEY
 * @property string $RM_CODE
 * @property string $RM_NAME
 * @property string $RM_NAMELAT
 */
class Room extends BaseModel
{
    protected $table = 'Rooms';

//    protected $appends = ['RM_KEY', 'RM_CODE', 'RM_NAME', 'RM_NAMELAT'];
/*
    public function getIdAttribute(){
        return $this->attributes['RM_KEY'];
    }

    public function getCodeAttribute(){
        return $this->attributes['RM_CODE'];
    }

    public function getNameAttribute(){
        return $this->attributes['RM_NAME'];
    }

    public function getNameLatAttribute(){
        return $this->attributes['RM_NAMELAT'];
    }*/

}
