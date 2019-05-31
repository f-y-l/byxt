<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Db;

class Admin extends Model
{
    // 软删除
    use SoftDelete;

    // 只读字段
    protected $readonly = ['email'];

    // 登陆
    public function login($data)
    {
        // 1.在校验模型中校验数据是否符合规范Admin.php
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('login')->check($data)) {
            return $validate->getError();
        }
        // 2.查询数据
//        $result = $this->where($data)->find();
        $result = Db::name('Admin')->where($data)->find();
        if($result){
            if($result['status'] != 1){
                return '账户处于禁用状态,等待管理员解禁';
            }
            $sessionData = [
                'id' => $result['id'],
                'nickname' => $result['nickname'],
                'email' => $result['email'],
                'is_super' => $result['is_super']
            ];
            session('admin', $sessionData);
            return 1;
        }else{
            return '账户或密码错误!';
        }
    }

    // 注册
    public function register($data, $usernamedata, $emaildata)
    {
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('Admin')->where($usernamedata)->find();
        if($username){
            return '用户已被注册';
        }else{
            $email = Db::name('Admin')->where('email','=',$emaildata)->find();
            if($email){
                return '邮箱已被注册';
            }else{
                // 只允许插入数据库中有的字段
                $result = $this->allowField(true)->save($data);
                if($result){
//                    mailto($data['email'],$data['nickname'],'注册管理员账户成功','尊敬的'.$data['nickname'].':欢迎你加入选课系统!请等待账户解禁！');
                    $sessionData = [
                        'id'=>$result['id'],
                        'nickname'=>$result['nickname'],
                    ];
                    session('admin',$sessionData);
                    return 1;
                }else{
                    return '注册失败';
                }
            }
        }
    }




    // 添加
    public function add($data){
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('Register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('admin')->where('username','=',$data['username'])->find();
        if($username){
            return '账户名已被注册';
        }else{
            $result = $this->allowField(true)->save($data);
            if($result){
                return 1;
            }else{
                return '添加失败';
            }
        }
    }

    // 设置
    public function edit($data){
        $validate = new\app\common\validate\Admin();
        if(!$validate->scene('Edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Admin')->find($data['id']);
        $adminInfo->nickname    = $data['nickname'];
        $adminInfo->status      = $data['status'];
        $adminInfo->is_super    = $data['is_super'];
        $result = $adminInfo->allowField(true)->save();
        if(session('admin.id')==$data['id']) {
            session('admin.nickname', $data['nickname']);
        }
        if($result){
            return 1;
        }else{
            return '设置失败';
        }
    }

    // 禁用
    public function status($data)
    {
        $validate = new \app\common\validate\Admin();
        if(!$validate->scene('Status')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Admin')->find($data['id']);
        $adminInfo->status = $data['status'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '操作失败';
        }
    }
}

