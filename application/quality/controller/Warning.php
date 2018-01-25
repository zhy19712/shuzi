<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/5
 * Time: 9:10
 */

namespace app\quality\controller;
use app\admin\controller\Base;
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
            $data['limit'] = $limit;
            $data['currentDate'] = $currentDate;
            return json(['code'=> 1, 'data' => $data]);
        }


        return $this->fetch();
    }
}