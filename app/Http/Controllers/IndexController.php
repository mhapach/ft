<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Country;

class IndexController extends Controller
{
    /**
     * ПЕрвая страница
     */
    public function index()
    {
//        dd(Country::where('cn_key',960)->get());
        return view('index');
    }

}
