<?php

namespace app\common\validate;

use think\Validate;

class College extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id|专业ID' => 'require|number',
        'college|专业名称' => 'require|max:50',
        'account|专业描述' => 'require|max:255'
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
        return $this->only(['college','account']);
    }

    public function  sceneEdit()
    {
        return $this->only(['id','college','account']);
    }
}
