<?php

namespace App\Models;
/**
 * App\Models\Resort
 *
 * @property string $id
 * @property string $name
 */
class Resort extends BaseModel
{
    protected $table = 'resorts';

//    protected $appends = ['id', 'name'];

//    protected $visible = ['id', 'name'];

    /**
     * Получить Страну
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'RS_CNKEY', 'СN_KEY');
    }

    /**
     * Получить отели
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'HD_RSKEY', 'RS_KEY');
    }
//
//    public function getIdAttribute(){
//        return $this->attributes['RS_KEY'];
//    }
//
//    public function getNameAttribute(){
//        return $this->attributes['RS_NAME'];
//    }

}
