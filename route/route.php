<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
    Route::group('admin',function (){
        //读者相关的路由配置
        Route::rule('login','admin/user/login','get|post');
        Route::rule('doregister','admin/user/doregister','get|post');
        Route::rule('register','admin/user/register','get|post');
        Route::rule('showbooks','admin/reader/showbooks','get|post');
        Route::rule('showbook/[:ISBN]','admin/reader/showbook','get|post');
        Route::rule('out','admin/user/out','get|post');
        Route::rule('searchbook/[:key]','admin/reader/searchbook','get|post');
        Route::rule('reset','admin/reader/reset','get|post');
        Route::rule('showreader','admin/reader/showreader','get|post');
        Route::rule('borrow/[:key]','admin/reader/borrow','get|post');
        Route::rule('showticket/[:key]','admin/reader/showticket','get|post');
        Route::rule('borrowbook','admin/reader/borrowbook','get|post');
        Route::rule('borrowbookjudge','admin/reader/borrowbookjudge','get|post');
        Route::rule('borrownotice/[:ID]','admin/reader/borrownotice','get|post');
        Route::rule('updatemyself','admin/reader/updatemyself','get|post');
        Route::rule('doupdate','admin/reader/doupdate','get|post');
        Route::rule('pay/[:ID]','admin/reader/pay','get|post');
        Route::rule('paysuccess/[:ID]','admin/reader/paysuccess','get|post');
        Route::rule('renewbook/[:bookID]/[:bS]','admin/reader/renewbook','get|post');
        Route::rule('returnbook/[:bookID]/[:bS]','admin/reader/returnbook','get|post');
        Route::rule('doreturn/[:ID]','admin/reader/doreturn','get|post');
        Route::rule('showbooklist/[:ISBN]','admin/reader/showbooklist','get|post');
        Route::rule('borrowreport/[:time]','admin/reader/borrowreport','get|post');



        //管理员相关的路由配置
        Route::rule('ashowbooks','admin/admin/ashowbooks','get|post');
        Route::rule('afindbook','admin/admin/asearchbook','get|post');
        Route::rule('areset','admin/admin/areset','get|post');
        Route::rule('ashowbook/[:ISBN]','admin/admin/ashowbook','get|post');
        Route::rule('ashowreader','admin/admin/ashowreader','get|post');
        Route::rule('ashowborrow','admin/admin/ashowborrow','get|post');
        Route::rule('ashowticket','admin/admin/ashowticket','get|post');
        Route::rule('addbookdescribe','admin/admin/addbookdescribe','get|post');
        Route::rule('deletebookdescribe/[:ISBN]','admin/admin/deletebookdescribe','get|post');
        Route::rule('editbookdescribe/[:ISBN]','admin/admin/editbookdescribe','get|post');
        Route::rule('addbook','admin/admin/addbook','get|post');
        Route::rule('deletebook/[:bookID]','admin/admin/deletebook','get|post');
        Route::rule('editbook/[:bookID]','admin/admin/editbook','get|post');
        Route::rule('editborrow/[:bookID]','admin/admin/editborrow','get|post');
        Route::rule('editticket/[:fineid]','admin/admin/editticket','get|post');
    });