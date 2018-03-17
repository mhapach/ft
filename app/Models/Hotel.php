<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
/**
 * App\Models\Hotel
 *
 * @property string $HD_KEY
 * @property string $HD_NAME
 * @property string $HD_ADDRESS
 * @property int $hd_trasnfer_strict
 * @property \App\Models\Stars $stars
 * @property \App\Models\Country $country
 * @property \App\Models\City $city
 * @property \App\Models\Resort $resort
 * @property \App\Models\Transfer $transfer
 */
class Hotel extends BaseModel
{
    protected $table = 'HotelDictionary';
    protected $primaryKey = 'HD_KEY';

    /**
     * Получить Страну
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'HD_CNKEY', 'CN_KEY');
    }

    /**
     * Получить Город
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'HD_CTKEY', 'CT_KEY');
    }

    /**
     * Получить Звездность
     */
    public function stars()
    {
        return $this->belongsTo(Stars::class, 'HD_COHId', 'COH_Id');
    }

    /**
     * Получить курорт
     */
    public function resort()
    {
        return $this->belongsTo(Resort::class, 'HD_RSKEY', 'RS_KEY');
    }

    /**
     * Получить курорт
     */
    public function transfer()
    {
        return $this->hasOne(Transfer::class, 'TF_KEY', 'hd_default_transfer_id');
    }


    /**
     * Список курортов
     * @param int $country_id
     * @param int $city_id
     * @param int $resort_id
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public static function getList($country_id, $city_id = 0, $resort_id = 0)
    {

        $aRes = DB::select(
            self::query_prepare("
                    SELECT *
                    FROM hoteldictionary
                    WHERE
                        --hd_web = 1 AND 
                        hd_cnkey = :nCountryId
                        AND (:nCityId = 0 OR hd_ctkey = :nCityId)
                        AND (:nResortId = 0 OR hd_rskey = :nResortId)
                        AND hd_key IN (
                           SELECT DISTINCT cs_code FROM tbl_Costs WHERE cs_svkey = 3 AND cs_code = hd_key AND CS_DATEEND > GETDATE()
                        )
                ",
                [
                    'nCountryId' => $country_id,
                    'nResortId' => $resort_id,
                    'nCityId' => $city_id
                ]
            )
        );

        return self::hydrate($aRes);

    }

    /*
        protected $appends = ['id', 'name', 'HD_KEY', 'HD_NAME'];

        public function getIdAttribute(){
            return $this->attributes['HD_KEY'];
        }

        public function getNameAttribute(){
            return $this->attributes['HD_NAME'];
        }
    */

}
