<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Db;

class Student extends Model
{
    // 软删除
    use SoftDelete;

    // 只读字段
    protected $readonly = ['email'];

    // 登陆
    public function login($data)
    {
        // 1.在校验模型中校验数据是否符合规范Student.php
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('login')->check($data)) {
            return $validate->getError();
        }
        // 2.查询数据
//        $result = $this->where($data)->find();
        $result = Db::name('Student')->where($data)->find();
        if($result){
            if($result['status'] != 1){
                return '账户处于禁用状态,等待老师确认';
            }
            $sessionData = [
                'id' => $result['id'],
                'nickname' => $result['nickname'],
                'email' => $result['email'],
                'teacher_id' => $result['teacher_id'],
                'college_id' => $result['college_id']
            ];
            session('student', $sessionData);
            return 1;
        }else{
            return '账户或密码错误!';
        }
    }

    // 注册
    public function register($data, $usernamedata, $emaildata)
    {
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('Student')->where($usernamedata)->find();
        if($username){
            return '用户已被注册';
        }else{
            $email = Db::name('Student')->where('email','=',$emaildata)->find();
            if($email){
                return '邮箱已被注册';
            }else{
                // 只允许插入数据库中有的字段
                $result = $this->allowField(true)->save($data);
                if($result){
//                    mailto($data['email'],$data['nickname'],'注册学生账户成功','尊敬的'.$data['nickname'].':欢迎你加入选课系统!请等待账户解禁！');
                    $sessionData = [
                        'id'=>$result['id'],
                        'nickname'=>$result['nickname'],
                    ];
                    session('student',$sessionData);
                    return 1;
                }else{
                    return '注册失败';
                }
            }
        }
    }


//// 验证邮箱
//    public function forget($data){
//        $validate = new \app\common\validate\Student();
//        if(!$validate->scene('forget')->check($data)){
//            return $validate->getError();
//        }
//        $email = Db::name('Student')->where('email','=',$data['email'])->find();
//        if($email){
//            return 1;
//        }else{
//            return '邮箱未被注册';
//        }
//    }
//
//    // 重置密码
//    public function reset($data)
//    {
//        $validate = new \app\common\validate\Student();
//        if(!$validate->scene('reset')->check($data)) {
//            return $validate->getError();
//        }
//        if($data['code'] != session('code')){
//            return '输入的验证码不正确';
//        }
//        $StudentInfo = Db::name('Student')->where('email','=',$data['email'])->find();
//        $password = mt_rand(100000000,999999999);
//        $StudentInfo['password'] = $password;
////        $result = $StudentInfo ->save();
//        $result = Db::name('Student')->where('email','=',$data['email'])->update(['password' => $password]);
//        if($result){
//            $content = '选课系统，密码重置成功! <br>用户名:' . $StudentInfo['username'] . '<br>新密码:' . $password;
//            mailto($StudentInfo['email'],$StudentInfo['nickname'],'重置密码成功',$content);
//            return 1;
//        }else{
//            return '邮件发送失败';
//        }
//    }

    // 添加
    public function add($data){
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('Register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('student')->where('username','=',$data['username'])->find();
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
        $validate = new\app\common\validate\Student();
        if(!$validate->scene('Edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Student')->find($data['id']);
        if($adminInfo['college_id'] != $data['college_id'] && ($adminInfo['teacher_id'] != 0 || $adminInfo['check_id'] != 0)){
            return '该学生课题已选，无法更改专业。';
        }
        if($adminInfo['college_id']!=$data['college_id']){
            $adminInfo->teacher_id = '0';
        }
        $adminInfo->nickname    = $data['nickname'];
        $adminInfo->status      = $data['status'];
        $adminInfo->college_id  = $data['college_id'];
        $result = $adminInfo->allowField(true)->save();
        if(session('?student')){
            session('student.nickname',$data['nickname']);
            session('student.college_id',$data['college_id']);
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
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('Status')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Student')->find($data['id']);
        if($adminInfo['teacher_id'] != 0 || $adminInfo['check_id'] != 0){
            return '该学生课题已选，无法禁用';
        }else{
            $adminInfo->status = $data['status'];
            $result = $adminInfo->allowField(true)->save();
            if($result){
                return 1;
            }else{
                return '操作失败';
            }
        }
    }

    // 个人设置
    public function person($data)
    {
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('person')->check($data)){
            return $validate->getError();
        }
        $studentInfo = model('student')->find($data['id']);
        if($studentInfo['check_id']!=0&&$studentInfo['college_id']!=$data['college_id']){
            return '课程已选择，无法更改专业！';
        }
        $studentInfo->nickname = $data['nickname'];
        $studentInfo->college_id = $data['college_id'];
        $studentInfo->password = $data['password'];
        $result = $studentInfo->allowField(true)->save();
        if($result){
            session('student.nickname',$data['nickname']);
            session('student.college_id',$data['college_id']);
            return 1;
        }else{
            return '设置失败';
        }
    }

    // 选课
    public function lock($data)
    {
        $validate = new \app\common\validate\Student();
        if(!$validate->scene('lock')->check($data)){
            return $validate->getError();
        }
        $studentInfo = model('Student')->find($data['id']);
        $studentInfo->check_id = $data['check_id'];
        $result = $studentInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '设置失败';
        }
    }

    // 取消
    public function nopass($data)
    {
        $studentInfo = $this->find($data['id']);
        $studentInfo->check_id = '0';
        $studentInfo->allowField(true)->save();
    }

    // 添加教师ID
    public function addteacher($data)
    {
        $studentInfo = model('Student')->where('id','=',$data['id'])->find();
        $studentInfo->teacher_id = $data['teacher_id'];
        $studentInfo->allowField(true)->save();
    }
}

