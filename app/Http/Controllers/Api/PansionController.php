<?php

namespace App\Http\Controllers\Api;

use App\Models\Pansion;

class PansionController extends BaseController
{
    /**
     * Список курортов
     * @param int $country_id
     * @param int $city_id
     * @param int $resort_id
     * @param int $hotel_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index($country_id, $city_id = 0, $resort_id = 0, $hotel_id = 0)
    {
        $oPansion = new Pansion();
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = $oPansion->getList($country_id, $city_id, $resort_id, $hotel_id);
        return response()->json([
            'aPansion' => Pansion::utf8_converter($oCollection->toArray())
        ]);
    }
}
