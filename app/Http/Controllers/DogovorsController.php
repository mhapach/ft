<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DogovorsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        /** @var \App\User $oUser */
        $oUser = \Auth::user();
        $oClient = $oUser->client;
        $aDogovors = $oClient->dogovors;
        return view('dogovors', ["oUser" => $oUser, 'aDogovors' => $aDogovors]);
    }
}
