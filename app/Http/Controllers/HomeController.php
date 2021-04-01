<?php

namespace App\Http\Controllers;

use App\Home;
use App\HomeType;
use App\Owner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

      //Đăng nhập mới xem được chi tiết bên trong
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
        $home_types = HomeType::all();

        return view('map', compact('homes', 'owners', 'home_types'));
    }

    public function search(Request $request){
        if ($request->ajax()) {
            $listOwners = Owner::where('name', 'LIKE', '%' . $request->search . '%')->get();

            $listIDOwners[] = null;
            foreach ($listOwners as $key => $item){
                $listIDOwners[$key] = $item->id;
            }

            return Response($listIDOwners);
        }
    }
}
