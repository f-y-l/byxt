<?php

namespace app\index\controller;

class StudentBase extends Base
{
    public function initialize()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }
    }
}
