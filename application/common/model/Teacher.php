<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Db;

class Teacher extends Model
{
    // 软删除
    use SoftDelete;

    // 只读字段
    protected $readonly = ['email'];

    // 关联课题
    public function tesk()
    {
        return $this->hasMany('tesk','teacher_id','id');
    }
    // 关联选课
    public function check()
    {
        return $this->hasMany('check','teacher_id','id');
    }


    // 登陆
    public function login($data)
    {
        // 1.在校验模型中校验数据是否符合规范
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('login')->check($data)) {
            return $validate->getError();
        }
        // 2.查询数据
//        $result = $this->where($data)->find();
        $result = Db::name('Teacher')->where($data)->find();
        if($result){
            if($result['status'] != 1){
                return '账户处于禁用状态,等待教务确认';
            }
            $sessionData = [
                'id' => $result['id'],
                'nickname' => $result['nickname'],
                'email' => $result['email'],
                'college_id' => $result['college_id']
            ];
            session('teacher', $sessionData);
            return 1;
        }else{
            return '账户或密码错误!';
        }
    }

    // 注册
    public function register($data, $usernamedata, $emaildata)
    {
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('Teacher')->where($usernamedata)->find();
        if($username){
            return '用户已被注册';
        }else{
            $email = Db::name('Teacher')->where('email','=',$emaildata)->find();
            if($email){
                return '邮箱已被注册';
            }else{
                // 只允许插入数据库中有的字段
                $result = $this->allowField(true)->save($data);
                if($result){
//                    mailto($data['email'],$data['nickname'],'注册教师账户成功','尊敬的'.$data['nickname'].':欢迎你加入选课系统!请等待账户解禁！');
                    $sessionData = [
                        'id'=>$result['id'],
                        'nickname'=>$result['nickname'],
                    ];
                    session('teacher',$sessionData);
                    return 1;
                }else{
                    return '注册失败';
                }
            }
        }
    }

    // 验证邮箱
    public function forget($data){
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('forget')->check($data)){
            return $validate->getError();
        }
        $email = Db::name('Teacher')->where('email','=',$data['email'])->find();
        if($email){
            return 1;
        }else{
            return '邮箱未被注册';
        }
    }

    // 重置密码
    public function reset($data)
    {
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('reset')->check($data)) {
            return $validate->getError();
        }
        if($data['code'] != session('code')){
            return '输入的验证码不正确';
        }
        $TeacherInfo = Db::name('Teacher')->where('email','=',$data['email'])->find();
        $password = mt_rand(100000000,999999999);
        $TeacherInfo['password'] = $password;
//        $result = $TeacherInfo ->save();
        $result = Db::name('Teacher')->where('email','=',$data['email'])->update(['password' => $password]);
        if($result){
            $content = '选课系统，密码重置成功! <br>用户名:' . $TeacherInfo['username'] . '<br>新密码:' . $password;
            mailto($TeacherInfo['email'],$TeacherInfo['nickname'],'重置密码成功',$content);
            return 1;
        }else{
            return '邮件发送失败';
        }
    }

    // 添加
    public function add($data){
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('Register')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('Teacher')->where('username','=',$data['username'])->find();
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
        $validate = new\app\common\validate\Teacher();
        if(!$validate->scene('Edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Teacher')->find($data['id']);
        $adminInfo->nickname    = $data['nickname'];
        $adminInfo->status      = $data['status'];
        $adminInfo->college_id  = $data['college_id'];
        $result = $adminInfo->allowField(true)->save();
        if(session('?teacher')) {
           session('teacher.nickname', $data['nickname']);
           session('teacher.college_id', $data['college_id']);
           session('teacher.status', $data['status']);
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
        $validate = new \app\common\validate\Teacher();
        if(!$validate->scene('Status')->check($data)){
            return $validate->getError();
        }
        $checkInfo = model('Tesk')->where('teacher_id','=',$data['id'])->find();
        if($checkInfo){
            return '该教师已开课，无法禁用';
        }
        $adminInfo = model('Teacher')->find($data['id']);
        $adminInfo->status = $data['status'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '操作失败';
        }
    }

    // 删除
    public function del($data){
        $checkInfo = model('Check')->where('teacher_id','=',$data)->select();
        for($i = 0 ; $i < $checkInfo->count(); $i ++){
            $checkInfo[$i]->status = 0;
            $checkInfo[$i]->lock = 0;
            $checkInfo[$i]->student_id = 0;
            $checkInfo[$i]->allowField(true)->save();
        }

        $studentInfo = model('Student')->where('teacher_id','=',$data)->select();
        for($n= 0 ; $n < $studentInfo->count(); $n ++){
            $studentInfo[$n]->college_id = 0;
            $studentInfo[$n]->teacher_id = 0;
            $studentInfo[$n]->check_id = 0;
            $studentInfo[$n]->allowField(true)->save();
        }

    }
}

