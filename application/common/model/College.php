<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Db;

class College extends Model
{
    use SoftDelete;

    // 关联教师
    public function teacher()
    {
        return $this->hasMany('teacher','college_id','id');
    }
    // 关联学生
    public function student()
    {
        return $this->hasMany('student','college_id','id');
    }
    // 关联课题
    public function tesk()
    {
        return $this->hasMany('tesk','college_id','id');
    }

    // 关联选课
    public function Check()
    {
        return $this->hasMany('check','college_id','id');
    }

    // 添加
    public function add($data){
        $validate = new \app\common\validate\College();
        if(!$validate->scene('add')->check($data)){
            return $validate->getError();
        }
        $username =  Db::name('College')->where('college','=',$data['college'])->find();
        if($username){
            return '专业已存在';
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
        $validate = new\app\common\validate\College();
        if(!$validate->scene('Edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('College')->find($data['id']);
        $adminInfo->college    = $data['college'];
        $adminInfo->account      = $data['account'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '设置失败';
        }
    }
}
