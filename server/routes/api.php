<?php

use Illuminate\Http\Request;

Route::any('viewProject', 'UserController@viewProject');
Route::any('signup', 'UserController@signup');
Route::any('signin', 'UserController@signin');
Route::any('signout', 'UserController@signout');

//Route::group(['middleware' => 'checkLogin'], function () {
    Route::any('getUserInfo', 'UserController@getUserInfo');
    Route::any('updateUserInfo', 'UserController@updateUserInfo');
    Route::any('toggleFocus', 'UserController@toggleFocus');
//});

Route::any('getProjList', 'ProjectController@getProjList');
Route::any('getProjTopList', 'ProjectController@getProjTopList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('addProject', 'ProjectController@addProject');

Route::any('uploadFile', 'FileController@uploadFile');

//min program api
Route::get('getproject/{id?}', 'ProjectController@getProInfo');
Route::get('getplist/{p?}{chid?}','ProjectController@getPList');
