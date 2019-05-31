<?php

namespace app\common\model;

use think\Model;
use think\model\concern\SoftDelete;

class Notice extends Model
{
    //软删除
    use SoftDelete;

    //添加
    public function add($data)
    {
        $validate = new \app\common\validate\Notice();
        if(!$validate->scene('add')->check($data)){
            return $validate->getError();
        }
        $result = $this->allowField(true)->save($data);
        if($result){
            return 1;
        }else{
            return '公告添加失败';
        }
    }

    // 设置
    public function edit($data){
        $validate = new\app\common\validate\Notice();
        if(!$validate->scene('edit')->check($data)){
            return $validate->getError();
        }
        $adminInfo = model('Notice')->find($data['id']);
        $adminInfo->title    = $data['title'];
        $adminInfo->content      = $data['content'];
        $result = $adminInfo->allowField(true)->save();
        if($result){
            return 1;
        }else{
            return '设置失败';
        }
    }
}
