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
class Admin extends Validate
{
    // Admin模型
    /* thinkPHP5 开发手册 验证规则 */
    protected $rule = [
        'username|管理员账户'  => 'require|max:20',
        'password|密码'       => 'require',
        'conpass|确认密码'     => 'require|confirm:password',
        'nickname|昵称'       => 'require|unique:admin',
        'email|邮箱'          => 'require|email',
        //|unique:admin
        'code|验证码'         => 'require|length:6',
        'status|管理员状态'    => 'require|between:0,1',
        'id|管理员ID'          => 'require|number',
        'is_super|管理权限'     =>'require|number|between:0,1'
    ];
    /* 设置验证信息 */
    protected $message = [
        'username.max'      => '账户长度不能超过 20',
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
        return $this->only(['username|unique:admin','password','conpass','nickname','email']);
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
        return $this->only(['id','nickname','status','is_super']);
    }
}