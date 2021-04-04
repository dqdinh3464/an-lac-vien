<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'register' => false,
    'verify' => false,
]);


Route::get("/", "HomeController@index")->name('home.index');

Route::get("/search", "HomeController@search")->name('home.search');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes();

Route::fallback(function(){
    abort(404, "Không tìm thấy trang bạn yêu cầu");
});

