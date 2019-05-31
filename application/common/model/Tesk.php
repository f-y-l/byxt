<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;
use think\Db;

class Tesk extends Model
{
    // 软删除
    use SoftDelete;

    // 关联选课表
    public function Check()
    {
        return $this->hasMany('Check','tesk_id','id');
    }

    // 添加
    public function add($data){
        $validate = new \app\common\validate\Tesk();
        if(!$validate->scene('add')->check($data)){
            return $validate->getError();
        }
        $result = $this->allowField(true)->save($data);
        model('Check')->add($data);
        if($result){
            return 1;
        }else{
            return '添加失败';
        }
    }

    // 设置
    public function edit($data){
        $validate = new\app\common\validate\Tesk();
        if(!$validate->scene('Edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Tesk')->find($data['id']);
        $adminInfo->tesk        = $data['tesk'];
        $adminInfo->description = $data['description'];
        $adminInfo->skill       = $data['skill'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '设置失败';
        }
    }

    // 禁用
    public function status($data)
    {
        $validate = new \app\common\validate\Tesk();
        if(!$validate->scene('status')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Tesk')->find($data['id']);
        $adminInfo->status = $data['status'];
        $result = $adminInfo->allowField(true)->save();
        if($data['status']==1){
            model('Check')->cstatus($data);
        }else{
            model('Check')->ncstatus($data);
        }
        if($result){
            return 1;
        }else{
            return '操作失败';
        }
    }

    // 删除
    public function del($data)
    {
        $taskInfo = $this->where('id','=',$data)->find();
        $taskInfo->status = 0;
        $taskInfo->allowField(true)->save();

        $checkInfo = model('Check')->where('tesk_id','=',$data)->find();
        $checkID = $checkInfo['id'];
        $checkInfo->status = 0;
        $checkInfo->lock = 0;
        $checkInfo->student_id = 0;
        $checkInfo->allowField(true)->save();

        $studentInfo = model('Student')->where('check_id','=',$checkID)->find();
        if($studentInfo){
            $studentInfo->teacher_id = 0;
            $studentInfo->check_id = 0;
            $studentInfo->allowField(true)->save();
        }
    }
}

