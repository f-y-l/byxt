<?php

namespace app\index\controller;

use think\Db;

class Check extends Base
{
    public function all ()
    {
        if(session('?student')){
            $studentInfo = model('Student')->where('id','=',session('student.id'))->field(['check_id','password'])->find();
            if($studentInfo['password']=='MDAwMDAwMDAwMIa2fd2HdLaX'){
                $this->redirect('./index/studentperson');
            }else{
                $teskInfo = model('Tesk')->select();
                $teacherInfo = model('Teacher')->where('college_id','=',session('student.college_id'))->field(['id','nickname'])->select();
                if(input('?get.teacher_id')&&input('get.teacher_id')!=0){
                    $checkInfo = model('Check')->where('teacher_id','=',input('get.teacher_id'))->where('cstatus','=','1')->order(['status'=>'desc','lock'=>'desc','id'])->paginate(10);
                }else{
                    if($studentInfo['check_id']!=0){
                        $checkInfo = model('Check')->where('college_id','=',session('student.college_id'))->where('cstatus','=','1')->order(['status','lock','id'])->paginate(10);
                    }else{
                        $checkInfo = model('Check')->where('college_id','=',session('student.college_id'))->where('cstatus','=','1')->order(['status'=>'desc','lock'=>'desc','id'])->paginate(10);
                    }
                }
                $viewDate = [
                    'check' => $checkInfo,
                    'teacher' => $teacherInfo,
                    'tesk' => $teskInfo,
                    'student' => $studentInfo
                ];
                $this->assign($viewDate);
            }
        }
        if(session('?teacher')){
            $checkInfo = model('Check')->where('teacher_id','=',session('teacher.id'))->order(['lock','status'=>'desc'])->paginate(30);
            $teacherInfo = model('Teacher')->field(['id','nickname'])->select();
            $teskInfo = model('Tesk')->where('teacher_id','=',session('teacher.id'))->select();
            $studentInfo = model('Student')->select();
            $viewDate = [
                'check' => $checkInfo,
                'teacher' => $teacherInfo,
                'tesk' => $teskInfo,
                'student' => $studentInfo
            ];
            $this->assign($viewDate);
        }
        return view();
    }
    // 通过pass
    public function nopass()
    {
        $checkInfo = model('Check')->find(input('post.id'));
        $studentId = [
            'id' => $checkInfo['student_id']
        ];
        model('Student')->nopass($studentId);
        $checkInfo->lock = '0';
        $checkInfo->student_id = '0';
        $result = $checkInfo->allowField(true)->save();
        if ($result){
            $this->success('设置成功','index/check/all');
        }else{
            $this->error('设置失败');
        }
    }

    // 通过pass
    public function pass()
    {
        $checkInfo = model('Check')->find(input('post.id'));
        $teacherId = [
            'teacher_id' => $checkInfo['teacher_id'],
            'id' => $checkInfo['student_id']
        ];
        model('Student')->addteacher($teacherId);
        $checkInfo->status = '1';
        $result = $checkInfo->allowField(true)->save();
        if ($result){
            $this->success('设置成功','index/check/all');
        }else{
            $this->error('设置失败');
        }
    }

    // 锁定
    public function lock()
    {
        $data = [
            'id' => input('post.id'),
            'lock' => input('post.lock')? 0 : 1,
            'student_id' => input('post.student_id')
        ];
        $datas = [
            'check_id' => input('post.id'),
            'id' => input('post.student_id')
        ];
        $checkInfo = model('Check')->field('status,lock')->find($data['id']);
        if($checkInfo['status']!=0||$checkInfo['lock']!=0){
            $this->error('课程已被选择');
        }else{
            $result = model('Check')->lock($data);
            $resultd = model('Student')->lock($datas);
            if($result == 1&&$resultd == 1){
                $this->success('操作成功','index/check/all');
            }else{
                $this->error($result);
                $this->error($resultd);
            }
        }
    }

    //确认
    public function status()
    {
        $data = [
            'id' => input('post.id'),
            'status' => input('post.status')? 0 : 1
        ];
        $result = model('Tesk')->status($data);
        if($result == 1){
            $this->success('操作成功','index/tesk/all');
        }else{
            $this->error($result);
        }

    }
}
