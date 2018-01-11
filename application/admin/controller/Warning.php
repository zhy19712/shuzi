<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 9:10
 */

namespace app\admin\controller;
use app\admin\model\ProjectModel;

class Warning extends Base
{
    public function index()
    {
        $warning = new ProjectModel();
        if(request()->isAjax()){
            $param = input('post.');
            $data = $warning->getOneProject($param['id']);
            return json(['data' => $data]);
        }
        return $this->fetch();
    }
}