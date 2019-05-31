<?php

namespace app\index\controller;

class AdminBase extends Base
{
    //初始化方法
    public function initialize()
    {
        if (!session('?admin')){
            $this->redirect('./index/home');
        }
    }
}
