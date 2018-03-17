<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Services\SearchHotels;
use App\Models\BaseModel;
use Faker\Provider\Base;
use Illuminate\Http\Request;

class SearchHotelsController extends BaseController
{
    /**
     * Список отелей ajax
     */
    public function index(Request $request)
    {
        $oSearchHotels = new SearchHotels();
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = $oSearchHotels->getResult($request);
        return $oCollection->toJson();
    }

}
