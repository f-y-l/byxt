<?php

namespace app\index\controller;

class Notice extends AdminBase
{
    public function all()
    {
        $noticeDate = model('notice')->order(['update_time'=>'desc','id'=>'desc'])->paginate('10');
        $viewDate = [
            'notice' => $noticeDate
        ];
        $this->assign($viewDate);
        return view();
    }

    public function del()
    {
        $noticeDate = model('Notice')->find(input('post.id'));
        $result = $noticeDate->delete();
        if($result){
            $this->success('删除成功','index/notice/all');
        }else{
            $this->error('删除失败');
        }
    }

    public function add()
    {
        if(request()->isAjax()){
            $data = [
                'title'     => input('post.title'),
                'content'   => input('post.content')
            ];
            $result = model('Notice')->add($data);
            if($result == 1){
                $this->success('添加成功','index/notice/all');
            }else{
                $this->error($result);
            }
        }
        return view();
    }

    public function edit()
    {
        if(request()->isAjax()){
            $data = [
                'id' => input('post.id'),
                'title'    => input('post.title'),
                'content'  => input('post.content')
            ];
            $result = model('Notice')->edit($data);
            if($result == 1){
                $this->success('保存成功','index/notice/all');
            }else{
                $this->error($result);
            }
        }
        $adminInfo = model('Notice')->find(input('id'));
        $viewData = [
            'notice' => $adminInfo
        ];
        $this->assign($viewData);
        return view();
    }
}
