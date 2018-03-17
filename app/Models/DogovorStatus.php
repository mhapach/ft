<?php

namespace App\Models;

/**
 * App\Models\DogovorStatus
 * @property int $OS_CODE
 * @property string $OS_NameLat
 */

class DogovorStatus extends BaseModel
{
    protected $table = 'Order_Status';


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
