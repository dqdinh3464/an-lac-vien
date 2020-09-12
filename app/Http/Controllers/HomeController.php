<?php

namespace App\Http\Controllers;

use App\Home;
use App\Owner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $homes = Home::all();
        $owners = Owner::all();

        return view('map', compact('homes', 'owners'));
    }
}
