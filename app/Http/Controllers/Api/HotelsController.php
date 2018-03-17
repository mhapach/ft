<?php

namespace App\Http\Controllers\Api;

use App\Models\Hotel;

class HotelsController extends BaseController
{
    /**
     * Список курортов
     * @param int $country_id
     * @param int $city_id
     * @param int $resort_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index($country_id, $city_id = 0, $resort_id = 0)
    {
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = Hotel::getList($country_id, $city_id, $resort_id);
        return response()->json([
            'aHotels' => Hotel::utf8_converter($oCollection->toArray())
        ]);
    }

}
