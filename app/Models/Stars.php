<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
/**
 * App\Models\Pansion
 *
 * @property int $COH_Id
 * @property string $COH_Name
 * @property string $COH_NameLat
 */
class Stars extends BaseModel
{
    protected $table = 'CategoriesOfHotel';

    /**
     * Список курортов
     * @param int $country_id
     * @param int $city_id
     * @param int $resort_id
     * @param int $hotel_id
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public static function getList($country_id, $city_id = 0, $resort_id = 0, $hotel_id = 0)
    {
        $aRes = DB::select(
            self::query_prepare("
                SELECT COH_Id, COH_Name, COH_NameLat   
                FROM CategoriesOfHotel
                WHERE
                    COH_Id IN (
                        SELECT DISTINCT HD_COHId
                        FROM hoteldictionary                          
                            INNER JOIN tbl_costs ON hd_key = cs_code
                                                    AND cs_svkey = 3
                                                    AND CS_DATEEND > GETDATE()
                        WHERE 
                            --hd_web = 1 AND 
                            hd_cnkey = :nCountryId
                            AND (:nCityId = 0 OR hd_ctkey = :nCityId)
                            AND (:nResortId = 0 OR hd_rskey = :nResortId)
                            AND (:nHotelId = 0 OR hd_key = :nHotelId)                            
                    )
                ORDER BY COH_PrintNum, COH_Name",
                [
                    'nCountryId' => $country_id,
                    'nResortId' => $resort_id,
                    'nCityId' => $city_id,
                    'nHotelId' => $hotel_id
                ]
            )
        );

        return self::hydrate($aRes);
    }
}
