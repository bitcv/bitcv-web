<?php

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

Route::group(['prefix'=>'admin'], function() {
    Route::pattern('path', '.+');
    Route::any('{path}',function($path){ 
        return view('admin');
    });
});

Route::pattern('path', '.+');
Route::any('{path}',function($path) {
    return view('index');
});

Route::get('/', function() {
    return view('index');
});
