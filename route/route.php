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

Route::group('index',function (){
    Route::rule('/','index/index/login','get|post');
    Route::rule('register','index/index/register','get|post');
//    Route::rule('forget','index/index/forget','get|post');
//    Route::rule('reset','index/index/reset','post');
    Route::rule('home','index/home/index','get');
    Route::rule('loginout','index/home/loginout','post');
    Route::rule('adminall','index/admin/all','get');
    Route::rule('adminadd','index/admin/add','get|post');
    Route::rule('admindel','index/admin/del','post');
    Route::rule('adminedit/[:id]','index/admin/edit','get|post');
    Route::rule('adminstauts','index/admin/status','post');
    Route::rule('studentall','index/student/all','get');
    Route::rule('studentadd','index/student/add','get|post');
    Route::rule('studentdel','index/student/del','post');
    Route::rule('studentedit/[:id]','index/student/edit','get|post');
    Route::rule('studentstauts','index/student/status','post');
    Route::rule('studentperson','index/student/person','get|post');
    Route::rule('teacherall','index/teacher/all','get');
    Route::rule('teacheradd','index/teacher/add','get|post');
    Route::rule('teacherdel','index/teacher/del','post');
    Route::rule('teacheredit/[:id]','index/teacher/edit','get|post');
    Route::rule('teacherstauts','index/teacher/status','post');
    Route::rule('noticeall','index/notice/all','get');
    Route::rule('noticeadd','index/notice/add','get|post');
    Route::rule('noticedel','index/notice/del','post');
    Route::rule('noticeedit/[:id]','index/notice/edit','get|post');
    Route::rule('collegeall','index/college/all','get');
    Route::rule('collegeadd','index/college/add','get|post');
    Route::rule('collegedel','index/college/del','post');
    Route::rule('collegeedit/[:id]','index/college/edit','get|post');
    Route::rule('taskall','index/tesk/all','get');
    Route::rule('taskalls','index/tesk/alls','get');
    Route::rule('taskadd','index/tesk/add','get|post');
    Route::rule('taskdel','index/tesk/del','post');
    Route::rule('taskedit/[:id]','index/tesk/edit','get|post');
    Route::rule('taskstatus','index/tesk/status','post');
    Route::rule('checkall/[:teacher_id]','index/check/all','get');
    Route::rule('checklock','index/check/lock','post');
    Route::rule('checkpass','index/check/pass','post');
    Route::rule('checknopass','index/check/nopass','post');
    Route::rule('checkdata','index/check/data','post');
});