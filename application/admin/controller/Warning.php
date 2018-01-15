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
            $limit = 0;
            if($data['cate'] == '开挖'){
                $limit = 7;

            }else if($data['cate'] == '支护'){
                $limit = 28;

            }else if($data['cate'] == '混凝土'){
                $limit = 28;
            }
            $currentDate = date("Y-m-d");
            return json(['data' => $data, 'limit' => $limit, 'currentDate' => $currentDate]);
        }
        return $this->fetch();
    }
}