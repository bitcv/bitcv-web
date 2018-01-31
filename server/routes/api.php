<?php

use Illuminate\Http\Request;

Route::any('signup', 'UserController@signup');
Route::any('signin', 'UserController@signin');
Route::any('signout', 'UserController@signout');
Route::any('getUserInfo', 'UserController@getUserInfo');
Route::any('updateUserInfo', 'UserController@updateUserInfo');
Route::any('focusProject', 'UserController@focusProject');
Route::any('viewProject', 'UserController@viewProject');

Route::any('getProjList', 'ProjectController@getProjList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('addProject', 'ProjectController@addProject');


Route::group(['prefix' => '/api/v1'], function () {
    Route::resource('project', 'ProjectController');
    Route::get('getplist/{p?}{chid?}','ProjectController@getPList');
});

//$api = app('Dingo\Api\Routing\Router');
//
//$api->version('v1', function ($api) {
//    $api->group(['namespace' => 'App\Http\Controllers'], function ($api) {
//        $api->get('project','ProjectController@index');
//    });
//});
$api = app('api.router');

$api->version('v1', ['prefix' => 'api'], function($api)
{
    $api->get('project', 'App\Http\Controllers\ProjectController@index');
    $api->get('project', 'App\Http\Controllers\ProjectController@pInfo');
    $api->get('pproject','App\Http\Controllers\ProjectController@pList');
});


























