<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 10:30
 */

namespace app\admin\validate;


use think\Validate;

class ProjectValidate extends Validate
{
    protected $rule = [
        ['id', 'unique:project', '工程划分信息已经存在']
    ];

}