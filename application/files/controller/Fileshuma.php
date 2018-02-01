<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/1/31
 * Time: 10:18
 */
namespace app\files\controller;


use app\admin\controller\Base;
use app\files\model\FileshumaModel;

class Fileshuma extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $shuma = new FileshumaModel();
            $param = input('post.');
            $data = $shuma->getOne($param['id']);
            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }
}