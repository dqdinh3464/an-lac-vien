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
//        dd(assertRegExp('^([0-9+])\w+', $request->search));
//        if($request->search == regex('^([0-9+])\w+')){
//            $datas = Owner::where('phonenumber', $value)->all();
//        }
//        else if($request->search == regex('^([A-Za-z])\w+')){
//            $datas = Owner::where('name', $value)->all();
//        }

        $find_owner= Owner::where('name', $request->search)->get();

        return view('search', compact($find_owner));
    }

    public function searchFullText(Request $request)
    {
        if ($request->search != '') {
            $data = Owner::FullTextSearch('name', $request->search)->get();
            foreach ($data as $key => $value) {
                echo $value->name;
                echo '<br>'; // mình viết vầy cho nhanh các bạn tùy chỉnh cho đẹp nhé
            }
        }
         return view('search', $data); //thay vì foreach như mình bạn có thể ném cái data vào 1 cái view nào đấy nhìn cho đẹp
    }
}
