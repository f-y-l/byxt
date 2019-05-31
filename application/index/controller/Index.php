<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function login()
    {
        if(session('?teacher.id') || session('?student.id') || session('?admin.id')){
            $this->redirect('index/home/index');
        }else{
            // post请求
            if(request()->isAjax()){
                $status = input('post.status');
                $data=[
                    'username'=>input('post.username'),
                    'password'=>think_encrypt(input('post.password'))
                ];
                if($status == 0){
                    $result = model('Admin')->login($data);
                }
                if($status == 1){
                    $result = model('Teacher')->login($data);
                }
                if($status == 2){
                    $result = model('Student')->login($data);
                }
                if($result == 1) {
                    $this->success('登陆成功','index/home/index');
                }else {
                    $this->error($result);
                }
            }
        }
        return view();
    }
    // 用户注册
    public function register()
    {
        if(request()->isAjax()){
            $status = input('post.status');
            $data = [
                'username'  => input('post.username'),
                'password'  => think_encrypt(input('post.password')),
                'conpass'   => think_encrypt(input('post.password')),
                'nickname'  => input('nickname'),
                'email'     => input('email'),
                'status'    => 0
            ];
            $username = ['username'  => input('post.username')];
            $email = $data['email'];
            if($status == 0) {
                $result = model('Admin')->register($data, $username, $email);
            }
            if($status == 1){
                $result = model('Teacher')->register($data, $username, $email);
            }
            if($status == 2){
                $result = model('Student')->register($data, $username, $email);
            }

            if($result == 1){
                $this->success('注册成功','index/index/login');
            }else{
                $this->error($result);
            }
        }
        return view();
    }

}
