<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Check extends Model
{
    use SoftDelete;

    public function add($data)
    {
        $teskid = model('Tesk')->where('tesk','=',$data['tesk'])->find();
        $number = $data['tesk_num'];
        $checkDate = [
            'tesk_id' => $teskid['id'],
            'teacher_id' => $data['teacher_id'],
            'college_id' => $data['college_id']
        ];
        $da = array();
        for($i= 0;$i< $number;$i++){
            $da[]=$checkDate;
        }
        $this->insertAll($da);
    }

    // 锁定
    public function lock($data)
    {
        $validate = new \app\common\validate\Check();
        if(!$validate->scene('lock')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Check')->find($data['id']);
        $adminInfo->lock = $data['lock'];
        $adminInfo->student_id = $data['student_id'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '操作失败';
        }
    }

    // 确认
    public function status($data)
    {
        $validate = new \app\common\validate\Tesk();
        if(!$validate->scene('status')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Tesk')->find($data['id']);
        $adminInfo->status = $data['status'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '操作失败';
        }
    }

    // cstatus
    public function cstatus($data){
        $check = $this->where('tesk_id','=',$data['id'])->select();
        $count = count($check);
        for($i=0;$i<$count;$i++){
            $check[$i]->cstatus = '1';
            $check[$i]->save();
        }
    }
    public function ncstatus($data){
        $check = $this->where('tesk_id','=',$data['id'])->select();
        $count = count($check);
        for($i=0;$i<$count;$i++){
            $check[$i]->cstatus = '0';
            $check[$i]->save();
        }
    }
}
