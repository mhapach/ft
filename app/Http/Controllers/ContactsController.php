<?php

namespace App\Http\Controllers;
use App\Helpers\CommonHelper;
use App\Models\Dogovor;
use App\Models\History;
use Illuminate\Http\Request;

use App\Models\Test;

class ContactsController extends Controller
{
    /**
     * ПЕрвая страница
     */
    public function index()
    {
        return view('contacts');
    }

}
