<?php

namespace App\Http\Controllers\Api;

use App\Models\Resort;

class ResortsController extends BaseController
{
    /**
     * Список курортов
     * @param int $country_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index($country_id)
    {
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = Resort::where([['RS_KEY', '>', '0'], ['RS_CNKEY', '=', $country_id]])
            ->has('hotels')
            ->orderBy('RS_NAME', 'asc')
            ->get();
        return response()->json([
            'aResorts' => Resort::utf8_converter($oCollection->toArray())
        ]);
    }

}
