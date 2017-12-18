<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/17
 * Time: 22:26
 */

namespace app\admin\validate;


use think\Validate;

class ContractValidate extends Validate
{
    protected $rule = [
        ['id', 'unique:contract', '合同信息已经存在']
    ];

}