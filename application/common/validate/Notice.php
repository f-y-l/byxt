<?php

namespace app\common\validate;

use think\Validate;

class Notice extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
	    'id|ID'             => 'require|number',
        'title|公告标题'    => 'require|max:50',
        'content|公告内容'  => 'require|max:20000'
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
        return $this->only(['title','content']);
    }
    public function sceneEdit()
    {
        return $this->only(['id','title','content']);
    }
}
