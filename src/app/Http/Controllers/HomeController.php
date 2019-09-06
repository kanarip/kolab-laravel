<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $location = geoip()->getLocation();

        return view('home', ['location' => $location]);
    }
}
