<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    //Для администратора
    public function index()
    {
        return view(
            'about',
        );
    }
}
