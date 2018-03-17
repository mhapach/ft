<?php

namespace App\Models;
/**
 * App\Models\Country
 *
 * @property string $CN_KEY
 * @property string $CN_NAMELAT
 */
class Country extends BaseModel
{
    protected $primaryKey = 'CN_KEY';
    protected $table = 'tbl_Country';

    //protected $appends = ['id', 'name'];

//    protected $visible = ['id', 'name'];

    /**
     * Получить список городов
     */
    public function cities()
    {
        return  $this->hasMany(City::class, 'CT_CNKEY', 'CN_KEY');
    }

    /**
     * Получить список курортов(не путать с регионами)
     */
    public function resorts()
    {
        return  $this->hasMany(Resort::class, 'RS_CNKEY', 'CN_KEY');
    }

    /**
     * Получить список курортов(не путать с регионами)
     */
    public function hotels()
    {
        return  $this->hasMany(Hotel::class, 'HD_CNKEY', 'CN_KEY');
    }
//
//    public function getIdAttribute(){
//        return $this->attributes['CN_KEY'];
//    }
//    public function getNameAttribute(){
//        return $this->attributes['CN_NAME'];
//    }
}
