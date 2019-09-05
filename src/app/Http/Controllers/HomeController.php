<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $location = geoip($_SERVER['REMOTE_ADDR']);

        return view('home', ['location' => $location]);
    }
}
