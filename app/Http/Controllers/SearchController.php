<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api\Services\SearchHotels;

class SearchController extends Controller
{
    /**
     * Страница результатов поиска
     */
    public function index(Request $request)
    {
        $oSearchHotels = new SearchHotels();
        $request->input('nPage',1);
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = $oSearchHotels->getResult($request);
        return view('search.index', [
            'aSearchResults' => $oCollection
        ]);
    }

    /**
     * Страница результатов поиска
     */
    public function detailed(Request $request)
    {
        $oSearchHotels = new SearchHotels();
        $request->input('nPage', 1);
        $request->input('nLimit', 200);
        /** @var \Illuminate\Database\Eloquent\Collection $oCollection */
        $oCollection = $oSearchHotels->getResult($request);
        return view('search.detailed', [
            'aSearchResults' => $oCollection
        ]);
    }

}
