<?php

namespace app\index\controller;

class College extends AdminBase
{
    public function all()
    {
        $studentDate = model('College')->order(['id'=>'asc'])->paginate(10);
        $viewDate = [
            'college' => $studentDate
        ];
        $this->assign($viewDate);
        return view();
    }

    //添加
    public function add()
    {
        if(request()->isAjax()){
            $data = [
                'college' => input('post.college'),
                'account' => input('post.account')
            ];
            $result = model('College')->add($data);
            if($result == 1){
                $this->success('专业添加成功','index/college/all');
            }else{
                $this->error($result);
            }
        }
        return view();
    }

    //删除
    public function del()
    {
        $adminInfo = model('College')->with('teacher,student,tesk')->find(input('post.id'));
        $result = $adminInfo->together('teacher,student,tesk')->delete();
        if($result){
            $this->success('删除成功','index/college/all');
        }else{
            $this->error('删除失败');
        }
    }

    //设置
    public function edit()
    {
        if(request()->isAjax()){
            $data = [
                'id'        => input('id'),
                'college' => input('post.college'),
                'account' => input('post.account')
            ];
            $result = model('College')->edit($data);
            if($result == 1){
                $this->success('编辑成功','index/college/all');
            }else{
                $this->error($result);
            }
        }
        $adminInfo = model('College')->find(input('id'));
        $viewData = [
            'college' => $adminInfo
        ];
        $this->assign($viewData);
        return view();
    }
}
