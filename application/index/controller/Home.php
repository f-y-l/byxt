<?php

namespace app\index\controller;

class Home extends Base
{
    //
    public function index()
    {
        $noticeDate = model('Notice')->order(['update_time'=>'desc','id'=>'desc'])->select();
        $viewDate = [
            'notice' => $noticeDate
        ];
        $this->assign($viewDate);
        return view();
    }

    public function loginout()
    {
        session(null);
        if(session('?admin.id') || session('?teacher.id') || session('?student.id')){
            $this->error('退出失败');
        }else{
            $this->success('退出成功','index/index/login');
        }
    }
}
