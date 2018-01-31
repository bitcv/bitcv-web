<?php

use Illuminate\Http\Request;

//Route::group(['middleware' => 'checkLogin'], function () {
    Route::any('updateUserInfo', 'UserController@updateUserInfo');
    Route::any('toggleFocus', 'UserController@toggleFocus');
    Route::any('viewProject', 'UserController@viewProject');
//});
Route::any('signup', 'UserController@signup');
Route::any('signin', 'UserController@signin');
Route::any('signout', 'UserController@signout');
Route::any('getUserInfo', 'UserController@getUserInfo');

Route::any('getProjList', 'ProjectController@getProjList');
Route::any('getProjTopList', 'ProjectController@getProjTopList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('addProject', 'ProjectController@addProject');


//Route::group(['prefix' => '/api/v1'], function () {
    Route::get('getProject/{proiId}', 'ProjectController@show');
    Route::get('getplist/{p?}{chid?}','ProjectController@getPList');
//});



























