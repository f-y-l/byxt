<?php

namespace app\common\validate;

use think\Validate;

class Check extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id|选课ID' => 'require|number',
        'college_id|专业ID' => 'require|number',
        'teacher_id|教师ID' => 'require|number',
        'student_id|学生ID' => 'require|number',
        'tesk_id|课题ID' => 'require|number',
        'lock|锁定状态' => 'require|number'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [];

    public function sceneAdd()
    {
        return $this->only(['college_id','teacher_id']);
    }

    public function sceneLock()
    {
        return $this->only(['id','lock','student_id']);
    }
}
