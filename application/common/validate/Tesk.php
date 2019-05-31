<?php

namespace app\common\validate;

use think\Validate;

class Tesk extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|课题ID' => 'require|number',
        'tesk|课程名称' => 'require|max:50|unique:Tesk',
        'tesk_num|课题数目' => 'require|number',
        'status|审核状态' => 'require|number|between:0,1',
        'college_id|专业ID' => 'require|number',
        'teacher_id|老师ID' => 'require|number',
        'description|课题描述' => 'require|max:2000',
        'demend|要求' => 'require|max:1000',
        'skill|技术需求' => 'require|max:1000'
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
        return $this->only(['college_id','teacher_id','tesk_num','tesk','description','demend','skill']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','tesk','description','demend','skill']);
    }

    public function sceneStatus(){
        return $this->only(['id','status']);
    }
}
