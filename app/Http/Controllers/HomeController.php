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
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $homes = Home::all()->sortBy('sort');


        return view('map', compact('homes'));
    }

    public function search(Request $request)
    {
        return view('map');
    }

    public function searchAjax(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Owner::where('name', 'LIKE', "%{$query}%")
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position: relative">';

            foreach($data as $row)
            {
                $output .= '
               <li><a style="font-size: 17px;" href="{{asset("/search/""'.$row->id.'")}}"'.'><i class="far fa-user"></i> '.$row->name.'</a></li>
               ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }
}
