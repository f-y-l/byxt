<?php

namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    //初始化方法
    public function initialize()
    {
        if(!session('?admin.id') && !session('?teacher.id') && !session('?student.id')) {
            $this->redirect('index/index/login');
        }
    }
}
