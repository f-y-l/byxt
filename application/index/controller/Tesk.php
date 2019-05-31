<?php

namespace app\index\controller;

use think\Db;

class Tesk extends StudentBase
{
    public function all()
    {
        $teacherInfo = model('Teacher')->where('id','=',session('teacher.id'))->find();
        if(session('?admin.id')){
            $taskDate = model('Tesk')->order(['status'=>'desc','college_id','teacher_id','id'=>'asc'])->paginate(10);
            $tasksDate = null;
        }else{
            $taskDate = model('Tesk')->where('teacher_id','=',session('teacher.id'))->order(['status'=>'desc','id'])->paginate(10);
            $tasksDate = model('Tesk')->where('college_id','=',$teacherInfo['college_id'])->where('teacher_id','<>',session('teacher.id'))->order(['status'=>'asc','id'])->paginate(10);
        }
        $teachersInfo = model('Teacher')->field(['id','nickname'])->select();
        $collegeInfo = model('College')->field(['id','college'])->select();
        $checkInfo = model('Check')->field(['tesk_id','status','lock'])->select();
        $viewDate = [
            'tesk' => $taskDate,
            'teacher' => $teachersInfo,
            'college' => $collegeInfo,
            'check' => $checkInfo,
            'tesks' => $tasksDate,
        ];
        $this->assign($viewDate);
        return view();
    }
    public function alls()
    {
        $teacherInfo = model('Teacher')->where('id','=',session('teacher.id'))->find();
        if(session('?admin.id')){
            $taskDate = model('Tesk')->order(['college_id','teacher_id','status'=>'desc','id'=>'asc'])->paginate(1000);
            $tasksDate = null;
        }else{
            $taskDate = model('Tesk')->where('teacher_id','=',session('teacher.id'))->order(['status'=>'desc','id'])->paginate(100);
            $tasksDate = model('Tesk')->where('college_id','=',$teacherInfo['college_id'])->where('teacher_id','<>',session('teacher.id'))->order(['status'=>'asc','id'])->paginate(10);
        }
        $teachersInfo = model('Teacher')->field(['id','nickname'])->select();
        $collegeInfo = model('College')->field(['id','college'])->select();
        $checkInfo = model('Check')->field(['tesk_id','status','lock','student_id'])->select();
        $studentInfo = Db::name('check')
            ->alias('a')
            ->join('student c','a.student_id = c.id')
            ->select();
        $viewDate = [
            'tesk' => $taskDate,
            'teacher' => $teachersInfo,
            'college' => $collegeInfo,
            'check' => $checkInfo,
            'tesks' => $tasksDate,
            'student' => $studentInfo
        ];
        $this->assign($viewDate);
        return view();
    }

    //添加
    public function add()
    {
        if(request()->isAjax()){
            $data = [
                'tesk' => input('post.tesk'),
                'description' => input('post.description'),
                'demend' => input('post.demend'),
                'skill' => input('skill'),
                'tesk_num' => input('post.tesk_num'),
                'teacher_id' => input('post.teacher_id'),
                'college_id' => input('post.college_id')
            ];
            $result = model('Tesk')->add($data);
            if($result == 1){
                $this->success('课题添加成功','index/tesk/all');
            }else{
                $this->error($result);
            }
        }
        return view();
    }

    //删除
    public function del()
    {
        $adminInfo = model('Tesk')->with('check')->find(input('post.id'));
        model('Tesk')->del(input('post.id'));
        $result = $adminInfo->together('check')->delete();
        if($result){
            $this->success('删除成功','index/tesk/all');
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
                'tesk'      => input('post.tesk'),
                'description' => input('post.description'),
                'demend'    => input('post.demend'),
                'skill'     => input('post.skill')
            ];
            $result = model('Tesk')->edit($data);
            if($result == 1){
                $this->success('编辑成功','index/tesk/all');
            }else{
                $this->error($result);
            }
        }
        $adminInfo = model('Tesk')->find(input('id'));
        $viewData = [
            'tesk' => $adminInfo
        ];
        $this->assign($viewData);
        return view();
    }

    //禁用
    public function status()
    {
        $data = [
            'id' => input('post.id'),
            'status' => input('post.status')? 0 : 1
        ];
        $checkData = model('Check')->where('tesk_id','=',$data['id'])->find();
        if($checkData['status']==1||$checkData['lock']==1){
            $this->error('课题已被学生选择，无法取消');
        }
        $result = model('Tesk')->status($data);
        if($result == 1){
            $this->success('操作成功','index/tesk/all');
        }else{
            $this->error($result);
        }

    }
}
