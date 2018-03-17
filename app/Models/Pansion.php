<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
/**
 * App\Models\Pansion
 *
 * @property string $PN_KEY
 * @property string $PN_NAME
 * @property string $PN_CODE
 * @property string $RM_NAMELAT
 * @property string $PN_Order
 * @property string $PN_GlobalCode
 * @property string $PN_Description
 */

class Pansion extends BaseModel
{
    protected $table = 'Pansion';
    protected $primaryKey = 'PN_KEY';

    /**
     * Список курортов
     * @param int $country_id
     * @param int $city_id
     * @param int $resort_id
     * @param int $hotel_id
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getList($country_id, $city_id = 0, $resort_id = 0, $hotel_id = 0)
    {

        $aRes = DB::select(
            self::query_prepare("
                SELECT Pansion.*
                FROM Pansion
                WHERE
                    PN_KEY IN (
                        SELECT DISTINCT CS_SUBCODE2
                        FROM tbl_costs
                            INNER JOIN hoteldictionary ON hd_key = cs_code
                                                          --AND hd_web = 1
                                                          AND hd_cnkey = :nCountryId
                                                          AND (:nCityId = 0 OR hd_ctkey = :nCityId)
                                                          AND (:nResortId = 0 OR hd_rskey = :nResortId)
                                                          AND (:nHotelId = 0 OR hd_key = :nHotelId)
                        WHERE
                            cs_svkey = 3
                            AND cs_subcode2 = PN_KEY
                            AND CS_DATEEND > GETDATE()
                    )
                ORDER BY PN_ORDER ASC",
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
