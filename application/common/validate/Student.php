<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/30
 * Time: 10:25
 */

namespace app\common\validate;

use think\Validate;

/* 管理员 验证器类 */
class Student extends Validate
{
    // Admin模型
    /* thinkPHP5 开发手册 验证规则 */
    protected $rule = [
        'username|学号'  => 'require|number|length:9',
        'password|密码'       => 'require',
        'conpass|确认密码'     => 'require|confirm:password',
        'nickname|姓名'       => 'require',
        'email|邮箱'          => 'require|email',
        //|unique:admin
        'code|验证码'         => 'require|length:6',
        'status|管理员状态'    => 'require|between:0,1',
        'id|管理员ID'          => 'require|number',
        'college_id|专业ID'   => 'require|number',
        'teacher_id|老师ID'   => 'require|number',
        'check_id|选题ID'     => 'require|number',
        'key|密码'       => 'require|min:6',
    ];
    /* 设置验证信息 */
    protected $message = [
        'username.length'      => '学号长度为9位',
        'conpass.confirm'   => '两次密码不一致',
        'code.length'       => '请输入正确的验证码'
    ];

    // 登陆场景
    public function sceneLogin()
    {
        return $this->only(['username','password']);
    }

    // 注册场景
    public function sceneRegister()
    {
        return $this->only(['username','password','conpass','nickname','email']);
    }

    // 发送验证码场景
    public function sceneForget()
    {
        return $this->only(['email']);
    }

    // 验证码验证场景
    public function sceneReset()
    {
        return $this->only(['email','code']);
    }

    // 禁用场景
    public function sceneStatus()
    {
        return $this->only(['id','status']);
    }

    // 设置
    public function sceneEdit()
    {
        return $this->only(['id','nickname','status','teacher_id','college_id']);
    }

    // 个人设置
    public function scenePerson()
    {
        return $this->only(['id','nickname','college_id','password','conpass','key']);
    }

    // 课题选择
    public function sceneLock()
    {
        return $this->only(['id','check_id']);
    }
}