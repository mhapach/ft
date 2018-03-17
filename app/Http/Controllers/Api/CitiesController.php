<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;

class CitiesController extends BaseController
{
    /**
     * Список стран
     * @param int $country_id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index($country_id)
    {
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = City::where([['CT_KEY', '>', '0'], ['CT_CNKEY', '=', $country_id]])
            ->has('hotels')
            ->orderBy('CT_NAME', 'asc')
            ->get();

        return response()->json([
            'aCities' => City::utf8_converter($oCollection->toArray())
        ]);
    }

}
