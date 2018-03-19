<?php

use Illuminate\Http\Request;

Route::any('updWxUserInfo', 'WxController@updWxUserInfo');
Route::any('addWxUser', 'WxController@addWxUser');
Route::any('getWxGroupId', 'WxController@getWxGroupId');
Route::any('wxLogin', 'WxController@wxLogin');

Route::any('viewProject', 'UserController@viewProject');
Route::any('signup', 'UserController@signup');
Route::any('signin', 'UserController@signin');
Route::any('signout', 'UserController@signout');
Route::any('getVcode','UserController@getVcode');
Route::any('checkVcode','UserController@checkVcode');
Route::any('resetPwd','UserController@resetPwd');
Route::any('getUserAsset','UserController@getUserAsset');
Route::any('getUserWallet','UserController@getUserWallet');
Route::any('getUserTransferRecord','UserController@getUserTransferRecord');
Route::any('withdraw','UserController@withdraw');
Route::any('addUserWallet','UserController@addUserWallet');

//Route::group(['middleware' => 'checkLogin'], function () {
Route::any('getUserInfo', 'UserController@getUserInfo');
Route::any('updateUserInfo', 'UserController@updateUserInfo');
Route::any('toggleFocus', 'UserController@toggleFocus');
//});
Route::any('uploadFile', 'FileController@uploadFile');

Route::any('getProjList', 'ProjectController@getProjList');
Route::any('getProjTopList', 'ProjectController@getProjTopList');
Route::any('getProjDetail', 'ProjectController@getProjDetail');
Route::any('getProjTagList', 'ProjectController@getProjTagList');
Route::any('getEnProjTagList', 'ProjectController@getEnProjTagList');

Route::any('getDepositBoxList', 'DepositController@getDepositBoxList');
Route::any('addDepositOrder', 'DepositController@addDepositOrder');
Route::any('getOrderDetail', 'DepositController@getOrderDetail');
Route::any('cancelDepositOrder', 'DepositController@cancelDepositOrder');
Route::any('getOrderTxRecordList', 'DepositController@getOrderTxRecordList');
Route::any('confirmDepositTx', 'DepositController@confirmDepositTx');
Route::any('getUserOrderList', 'DepositController@getUserOrderList');

Route::any('addDepositBox', 'AdminController@addDepositBox');
Route::any('delDepositBox', 'AdminController@delDepositBox');
Route::any('getBoxTxRecordList', 'AdminController@getBoxTxRecordList');
Route::any('confirmBoxTx', 'AdminController@confirmBoxTx');
Route::any('getProjDepositBoxList', 'AdminController@getProjDepositBoxList');
Route::any('getProjDepositOrderList', 'AdminController@getProjDepositOrderList');

Route::any('adminSignin', 'AdminController@signin');
Route::any('adminSignout', 'AdminController@signout');
//Route::any('adminSignin', 'AdminController@signin');
//Route::any('adminSignout', 'AdminController@signout');
//min program api
Route::any('getproject/{id?}', 'ProjectController@getProInfo');
Route::any('getplist/{p?}{chid?}','ProjectController@getPList');

Route::any('getNewsList','NewsController@getNewsList');
Route::any('getNewsDetail/{id?}','NewsController@getNewsDetail');
Route::any('getWeChatList','NewsController@getWeChatList');
Route::any('articleLists','NewsController@articleList');

//Route::any('articleList','NewsController@articleList');

//获取自己的登录信息
Route::any('getUser', 'AdminController@getUser');

//个人登录后的接口
Route::group(['middleware' => 'checkLogin'], function() {
    Route::post('apply', 'AdminController@apply');
});

//超级管理员接口
Route::group(['middleware' => 'checkAdmin'], function () {
    Route::any('addProject', 'AdminController@addProject');
    Route::any('authProject', 'AdminController@authProject');
    Route::any('clearProjAuth', 'AdminController@clearProjAuth');
    Route::any('delProject', 'AdminController@delProject');
    
    Route::any('addMedia', 'AdminController@addMedia');
    Route::any('updMedia', 'AdminController@updMedia');
    Route::any('delMedia', 'AdminController@delMedia');
    Route::any('updMedia', 'AdminController@updMedia');

    Route::any('addSocial', 'AdminController@addSocial');
    Route::any('updSocial', 'AdminController@updSocial');
    Route::any('delSocial', 'AdminController@delSocial');
    Route::any('updSocial', 'AdminController@updSocial');

    Route::any('getTokenList', 'AdminController@getTokenList');
    Route::any('addToken', 'AdminController@addToken');
    Route::any('updToken', 'AdminController@updToken');
    Route::any('delToken', 'AdminController@delToken');
    Route::any('updToken', 'AdminController@updToken');

    Route::any('getAdminDepositOrderList', 'AdminController@getAdminDepositOrderList');
    Route::any('delMediaReport','AdminController@delMediaReport');
    Route::any('getMediaReportList','AdminController@getMediaReportList');
    Route::any('getMediaReportCount','AdminController@getMediaReportCount');

    Route::any('getInstituList','AdminController@getInstituList');
    Route::any('delInstitu','AdminController@delInstitu');
    Route::any('addInstitu','AdminController@addInstitu');
    Route::any('updInstitu','AdminController@updInstitu');

    Route::any('getPerList','AdminController@getPerList');
    Route::any('delPerson','AdminController@delPerson');
    Route::any('addPerson','AdminController@addPerson');
    Route::any('updPerson','AdminController@updPerson');

    Route::any('getExchangeList','AdminController@getExchangeList');
    Route::any('delExchange','AdminController@delExchange');
    Route::any('addExchange','AdminController@addExchange');
    Route::any('updExchange','AdminController@updExchange');

    //财务交易记录
    Route::any('getFinanceList','FinanceController@getFinanceList');
    Route::any('getFinanceCount', 'FinanceController@getFinanceCount');
    Route::any('updateRecords', 'FinanceController@updateRecords');
    Route::any('exportRecords','FinanceController@exportRecords');
    Route::any('addWallets', 'FinanceController@addWallets');
    Route::any('getWalletList','FinanceController@getWalletList');
    Route::any('delWalletAddr', 'FinanceController@delWalletAddr');

    Route::any('getProjWxNum','AdminController@getProjWxNum');
    Route::any('getProjWbNum','AdminController@getProjWbNum');
    Route::any('getProjGitNum','AdminController@getProjGitNum');
    Route::any('getProjFbNum','AdminController@getProjFbNum');
    Route::any('getProjTwNum','AdminController@getProjTwNum');

    Route::any('getProjNum','AdminController@getProjNum');
    Route::any('getProjPass','AdminController@getProjPass');
    Route::any('getEdited','AdminController@getEdited');
    Route::any('getUpdated','AdminController@getUpdated');

    Route::any('getAllPermission','AdminController@getAllPermission');
    Route::any('addUser','AdminController@addUser');
    Route::any('updUser','AdminController@updUser');
    Route::any('delUser','AdminController@delUser');

});


    Route::any('getAdminDepositBoxList', 'AdminController@getAdminDepositBoxList');
    Route::any('getInstituNameList','AdminController@getInstituNameList');
    Route::any('getProjAdvisorList', 'AdminController@getProjAdvisorList');
    Route::any('getProjPartnerList', 'AdminController@getProjPartnerList');
    Route::any('getProjExchangeList','AdminController@getProjExchangeList');
    Route::any('getExchangeNameList','AdminController@getExchangeNameList');
    Route::any('getProjReportList', 'AdminController@getProjReportList');
    Route::any('getMediaList', 'AdminController@getMediaList');
    Route::any('getAdminList','AdminController@getAdminList');
    Route::any('addAdmin','AdminController@addAdmin');
    Route::any('delAdmin','AdminController@delAdmin');
    Route::any('updAdmin','AdminController@updAdmin');
    Route::any('getUserList','AdminController@getUserList');
    Route::any('cancelOperate','AdminController@cancelOperate');
    Route::any('authOperate','AdminController@authOperate');
    Route::any('getUserSearch','AdminController@getUserSearch');
    Route::any('inspectCode','AdminController@inspectCode');


    Route::any('eachDynamic','AdminController@eachDynamic');
    Route::any('getDynamic','AdminController@getDynamic');

    Route::any('getInstituNameList','AdminController@getInstituNameList');
    Route::any('getProjAdvisorList', 'AdminController@getProjAdvisorList');
    Route::any('getProjPartnerList', 'AdminController@getProjPartnerList');
    Route::any('getProjExchangeList','AdminController@getProjExchangeList');

    Route::any('getExchangeNameList','AdminController@getExchangeNameList');
    Route::any('getProjReportList', 'AdminController@getProjReportList');
    Route::any('getMediaList', 'AdminController@getMediaList');

//项目管理员只能操作自己的projId
//Route::group(['middleware' => 'checkProj'], function () {

    Route::any('getSocialList', 'AdminController@getSocialList');
    
    Route::any('getProjBasicInfo', 'AdminController@getProjBasicInfo');
    Route::any('getProjBasicList', 'AdminController@getProjBasicList');
    Route::any('updProjBasicInfo', 'AdminController@updProjBasicInfo');

    Route::any('getProjMemberList', 'AdminController@getProjMemberList');
    Route::any('addProjMember', 'AdminController@addProjMember');
    Route::any('addProjIMember','AdminController@addProjIMember');
    Route::any('delProjMember', 'AdminController@delProjMember');
    Route::any('updProjMember', 'AdminController@updProjMember');

    Route::any('getProjEventList', 'AdminController@getProjEventList');
    Route::any('addProjEvent', 'AdminController@addProjEvent');
    Route::any('delProjEvent', 'AdminController@delProjEvent');
    Route::any('updProjEvent', 'AdminController@updProjEvent');

    Route::any('getProjSocialList', 'AdminController@getProjSocialList');
    Route::any('addProjSocial', 'AdminController@addProjSocial');
    Route::any('delProjSocial', 'AdminController@delProjSocial');
    Route::any('updProjSocial', 'AdminController@updProjSocial');

    Route::any('addProjAdvisor', 'AdminController@addProjAdvisor');
    Route::any('delProjAdvisor', 'AdminController@delProjAdvisor');
    Route::any('updProjAdvisor', 'AdminController@updProjAdvisor');


    Route::any('addProjPartner', 'AdminController@addProjPartner');
    Route::any('addProjIPartner','AdminController@addProjIPartner');
    Route::any('delProjPartner', 'AdminController@delProjPartner');
    Route::any('updProjPartner', 'AdminController@updProjPartner');


    Route::any('addProjReport', 'AdminController@addProjReport');
    Route::any('delProjReport', 'AdminController@delProjReport');
    Route::any('updProjReport', 'AdminController@updProjReport');


    Route::any('addProjExchange','AdminController@addProjExchange');
    Route::any('addProjIExchange','AdminController@addProjIExchange');
    Route::any('delProjExchange','AdminController@delProjExchange');
    Route::any('updProjExchange','AdminController@updProjExchange');


    Route::any('getAdvList','AdminController@getAdvList');
    Route::any('addProjAdvisor','AdminController@addProjAdvisor');
    Route::any('addProjIAdvisor','AdminController@addProjIAdvisor');
    Route::any('delProjAdvisor','AdminController@delProjAdvisor');


//});

