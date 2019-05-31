<?php

namespace app\index\controller;

class Admin extends AdminBase
{
    public function all()
    {
        $adminDate = model('Admin')->order(['is_super'=>'asc','status'=>'asc','id'=>'asc'])->paginate(10);
        $viewDate = [
            'admin' => $adminDate
        ];
        $this->assign($viewDate);
        return view();
    }

    //添加
    public function add()
    {
        if(request()->isAjax()){
            $data = [
                'username' => input('post.username'),
                'nickname' => input('post.nickname'),
                'password' => input('post.password'),
                'conpass'  => input('post.dpassword'),
                'email'    => input('post.email')
            ];
            $result = model('Admin')->add($data);
            if($result == 1){
                $this->success('管理员添加成功','index/admin/all');
            }else{
                $this->error($result);
            }
        }
        return view();
    }

    //删除
    public function del()
    {
        $adminInfo = model('admin')->find(input('post.id'));
        $result = $adminInfo->delete();
        if($result){
            if(session('admin.id') == input('post.id')){
                session(null);
            }
            $this->success('删除成功','index/admin/all');
        }else{
            $this->error('删除失败');
        }
    }

    //设置
    public function edit()
    {
        if(request()->isAjax()){
            $data = [
                'id'        => input('id'),
                'status'    => input('post.status'),
                'nickname'  => input('post.nickname'),
                'is_super'  => input('post.is_super')
            ];
            $result = model('Admin')->edit($data);
            if($result == 1){
                $this->success('编辑成功','index/admin/all');
            }else{
                $this->error($result);
            }
        }
        if(session('admin.id')!=input('id')&&session('admin.is_super')==0){
            $this->redirect('./index/home');
        }
        $adminInfo = model('Admin')->find(input('id'));
        $viewData = [
            'admin' => $adminInfo
        ];
        $this->assign($viewData);
        return view();
    }

    //禁用
    public function status()
    {
        $data = [
            'id' => input('post.id'),
            'status' => input('post.status')? 0 : 1
        ];
        $result = model('Admin')->status($data);
        if($result == 1){
            $this->success('操作成功','index/admin/all');
        }else{
            $this->error($result);
        }

    }
}
