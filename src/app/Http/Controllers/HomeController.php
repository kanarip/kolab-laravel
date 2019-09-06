<?php

namespace Kolab\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $location = geoip()->getLocation(geoip()->getClientIP());

        return view('home', ['location' => $location]);
    }
}
