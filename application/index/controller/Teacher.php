<?php

namespace app\index\controller;

class Teacher extends StudentBase
{
    public function all()
    {
        $teacherDate = model('Teacher')->order(['status'=>'asc','id'=>'asc'])->paginate(10);
        $collegeDate = model('College')->field(['id','college'])->select();
        $taskInfo    = model('Tesk')->field('teacher_id')->distinct(true)->select();
        $viewDate = [
            'teacher' => $teacherDate,
            'college' => $collegeDate,
            'tesk'    => $taskInfo
        ];
        $this->assign($viewDate);
        return view();
    }
    //添加
    public function add()
    {
        if(request()->isAjax()){
            $data = [
                'username' => input('post.username'),
                'nickname' => input('post.nickname'),
                'password' => think_encrypt(input('post.password')),
                'conpass'  => think_encrypt(input('post.password')),
                'email'    => input('post.email'),
                'college_id'=> input('post.college_id')
            ];
            $result = model('Teacher')->add($data);
            if($result == 1){
                $this->success('教师添加成功','index/teacher/add');
            }else{
                $this->error($result);
            }
        }
        $collegeInfo = model('College')->field(['id','college'])->select();
            $viewData = [
                'college' => $collegeInfo
            ];
            $this->assign($viewData);
            return view();
        return view();
    }

    //删除
    public function del()
    {
        $adminInfo = model('Teacher')->with('check,tesk')->find(input('post.id'));
        model('Teacher')->del(input('post.id'));
        $result = $adminInfo->together('check,tesk')->delete();
        if($result){
            if(session('teacher.id') == input('post.id')){
                session(null);
            }
            $this->success('删除成功','./teacherall');
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
                'status'    => input('post.status'),
                'nickname'  => input('post.nickname'),
                'college_id'=> input('post.college_id')
            ];
            $teacherInfo = model('Teacher')->where('id','=',$data['id'])->find();
            $teskInfo = model('Tesk')->where('teacher_id','=',$data['id'])->select();
            if($teskInfo->count()!=0 && $teacherInfo['college_id']!=$data['college_id']){
                $this->error('课题已开设，无法更改专业');
            }else{
                $result = model('Teacher')->edit($data);
                if($result == 1){
                    $this->success('编辑成功','index/teacher/all');
                }else{
                    $this->error($result);
                }
            }
        }
        if(session('teacher.id')==input('id')||session('?admin')){
            $teacherInfo = model('Teacher')->find(input('id'));
            $collegeInfo = model('College')->field(['id','college'])->select();
            $viewData = [
                'teacher' => $teacherInfo,
                'college' => $collegeInfo
            ];
            $this->assign($viewData);
            return view();
        }else{
            $this->redirect('./index/home');
        }
    }

    //禁用
    public function status()
    {
        $data = [
            'id' => input('post.id'),
            'status' => input('post.status')? 0 : 1
        ];
        $result = model('Teacher')->status($data);
        if($result == 1){
            $this->success('操作成功','index/teacher/all');
        }else{
            $this->error($result);
        }

    }
}
