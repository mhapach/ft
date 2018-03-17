<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;

class CountriesController extends BaseController
{
    /**
     * Список стран
     */
    public function index()
    {

        /** @var Collection $oCollection */
        $oCollection = Country::where([['CN_KEY', '>', '0'], ['CN_WEB', '=', '1']])
            ->wherehas('hotels', function ($query) {
                $query->where('HD_WEB', '=', '1');
            } )
            ->orderBy('CN_NAME', 'asc')
            ->get();

        return response()->json([
            'aCountries' => Country::utf8_converter($oCollection->toArray())
        ]);
    }





}
