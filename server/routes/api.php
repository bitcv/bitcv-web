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
Route::any('getProjTopList', 'ProjectController@getProjTopList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('addProject', 'ProjectController@addProject');
