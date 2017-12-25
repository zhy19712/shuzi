<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/25
 * Time: 9:32
 */

namespace app\admin\validate;


use think\Validate;

class ProjectDivideValidate extends Validate
{
    protected $rule = [
        ['id', 'unique:project_divide', '节点已经存在']
    ];
}