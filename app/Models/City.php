<?php

namespace App\Models;

/**
 * App\Models\City
 *
 * @property INT $CT_KEY
 * @property string $CT_NAME
 */

class City extends BaseModel
{
    protected $primaryKey = 'CT_KEY';
    protected $table = 'CityDictionary';

    /**
     * Получить Страну
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'CT_CNKEY', 'CN_KEY');
    }

    /**
     * Получить отели
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'HD_CTKEY', 'CT_KEY');
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
