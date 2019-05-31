<?php

namespace app\index\controller;

class Student extends Base
{
    public function all()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }else {
            if(session('teacher.college_id')){
                $studentDate = model('Student')->where('college_id','=',session('teacher.college_id'))->order(['status' => 'desc', 'teacher_id' => 'asc', 'id', 'check_id', 'college_id'])->paginate(10);
                $studentsDate = model('Student')->where('teacher_id','=',session('teacher.id'))->order(['status' => 'desc', 'teacher_id' => 'asc', 'id', 'check_id', 'college_id'])->paginate(100);
                $teacherInfo = model('Teacher')->field(['id', 'nickname'])->select();
                $collegeInfo = model('College')->field(['id', 'college'])->select();
                $viewDate = [
                    'student' => $studentDate,
                    'students' => $studentsDate,
                    'teacher' => $teacherInfo,
                    'college' => $collegeInfo
                ];
                $this->assign($viewDate);
                return view();
            }else if(session('?admin')){
                $studentDate = model('Student')->order(['check_id','status' => 'desc', 'teacher_id' => 'asc', 'id',  'college_id'])->paginate(10);
                $teacherInfo = model('Teacher')->field(['id', 'nickname'])->select();
                $collegeInfo = model('College')->field(['id', 'college'])->select();
                $viewDate = [
                    'student' => $studentDate,
                    'teacher' => $teacherInfo,
                    'college' => $collegeInfo,
                    'students' => null
                ];
                $this->assign($viewDate);
                return view();
            }
        }
    }
    //添加
    public function add()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }else {
            if (request()->isAjax()) {
                $data = [
                    'username' => input('post.username'),
                    'nickname' => input('post.nickname'),
                    'password' => think_encrypt(input('post.password')),
                    'conpass' => think_encrypt(input('post.password')),
                    'email' => input('post.email')
                ];
                $result = model('Student')->add($data);
                if ($result == 1) {
                    $this->success('学生添加成功', 'index/student/add');
                } else {
                    $this->error($result);
                }
            }
            return view();
        }
    }

    //删除
    public function del()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }else {
            $adminInfo = model('Student')->find(input('post.id'));
            $result = $adminInfo->delete();
            if ($result) {
                if (session('student.id') == input('post.id')) {
                    session(null);
                }
                $this->success('删除成功', 'index/student/all');
            } else {
                $this->error('删除失败');
            }
        }
    }

    //设置
    public function edit()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }else {
            if (request()->isAjax()) {
                $data = [
                    'id' => input('id'),
                    'status' => input('post.status'),
                    'nickname' => input('post.nickname'),
                    'college_id' => input('post.college_id'),
                    'teacher_id' => input('post.teacher_id')
                ];
                $result = model('Student')->edit($data);
                if ($result == 1) {
                    $this->success('编辑成功', 'index/student/all');
                } else {
                    $this->error($result);
                }
            }
            $adminInfo = model('Student')->find(input('id'));
            $teacherInfo = model('Teacher')->field(['id', 'nickname'])->select();
            $collegeInfo = model('College')->field(['id', 'college'])->select();
            $key = think_decrypt($adminInfo['password']);
            $viewData = [
                'student' => $adminInfo,
                'teacher' => $teacherInfo,
                'college' => $collegeInfo,
                'key'     => $key
            ];
            $this->assign($viewData);
            return view();
        }
    }

    //禁用
    public function status()
    {
        if(session('?student')){
            $this->redirect('./index/home');
        }else {
            $data = [
                'id' => input('post.id'),
                'status' => input('post.status') ? 0 : 1
            ];
            $result = model('Student')->status($data);
            if ($result == 1) {
                $this->success('操作成功', 'index/student/all');
            } else {
                $this->error($result);
            }
        }
    }

    public function person()
    {
        if(request()->isAjax()){
            $data = [
                'id' => input('post.id'),
                'nickname' => input('post.nickname'),
                'college_id' => input('post.college_id'),
                'password'  => think_encrypt(input('post.password')),
                'conpass'   => think_encrypt(input('post.conpass')),
                'key'       => input('post.password')
            ];
            if($data['key'] == '123456'){
                $this->error("请初始化你的密码");
            }else{
                $result = model('student')->person($data);
                if($result == 1){
                    $this->success('设置成功','index/home/index');
                }else{
                    $this->error($result);
                }
            }
        }
        if(session('?student.id')){
            $stdentInfo = model('Student')->find(session('student.id'));
            $collegeInfo = model('College')->select();
            $pass = think_decrypt($stdentInfo['password']);
            $viewDate = [
                'student' => $stdentInfo,
                'college' => $collegeInfo,
                'key'     => $pass
            ];
            $this->assign($viewDate);
            return view();
        }else{
            $this->redirect('./index/home');
        }

    }
}
