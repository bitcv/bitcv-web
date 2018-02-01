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
Route::any('getProjTagList', 'ProjectController@getProjTagList');
Route::any('getMediaList', 'ProjectController@getMediaList');
Route::any('getSocialList', 'ProjectController@getSocialList');
Route::any('getProjBasicInfo', 'ProjectController@getProjBasicInfo');
Route::any('getProjBasicList', 'ProjectController@getProjBasicList');
Route::any('updProjBasicInfo', 'ProjectController@updProjBasicInfo');
Route::any('delProject', 'ProjectController@delProject');

Route::any('getProjMemberList', 'ProjectController@getProjMemberList');
Route::any('addProjMember', 'ProjectController@addProjMember');
Route::any('delProjMember', 'ProjectController@delProjMember');
Route::any('updProjMember', 'ProjectController@updProjMember');

Route::any('getProjEventList', 'ProjectController@getProjEventList');
Route::any('addProjEvent', 'ProjectController@addProjEvent');
Route::any('delProjEvent', 'ProjectController@delProjEvent');
Route::any('updProjEvent', 'ProjectController@updProjEvent');

Route::any('getProjSocialList', 'ProjectController@getProjSocialList');
Route::any('addProjSocial', 'ProjectController@addProjSocial');
Route::any('delProjSocial', 'ProjectController@delProjSocial');
Route::any('updProjSocial', 'ProjectController@updProjSocial');

Route::any('getProjAdvisorList', 'ProjectController@getProjAdvisorList');
Route::any('addProjAdvisor', 'ProjectController@addProjAdvisor');
Route::any('delProjAdvisor', 'ProjectController@delProjAdvisor');
Route::any('updProjAdvisor', 'ProjectController@updProjAdvisor');

Route::any('getProjPartnerList', 'ProjectController@getProjPartnerList');
Route::any('addProjPartner', 'ProjectController@addProjPartner');
Route::any('delProjPartner', 'ProjectController@delProjPartner');
Route::any('updProjPartner', 'ProjectController@updProjPartner');

Route::any('getProjReportList', 'ProjectController@getProjReportList');
Route::any('addProjReport', 'ProjectController@addProjReport');
Route::any('delProjReport', 'ProjectController@delProjReport');
Route::any('updProjReport', 'ProjectController@updProjReport');

Route::any('uploadFile', 'FileController@uploadFile');

//min program api
Route::get('getproject/{id?}', 'ProjectController@getProInfo');
Route::get('getplist/{p?}{chid?}','ProjectController@getPList');
