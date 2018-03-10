<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/9
 * Time: 13:45
 */

namespace app\safety\controller;

// 专题教育培训
use app\admin\controller\Base;
use app\safety\model\EducationModel;

class Education extends Base
{
    public function index()
    {
        return $this ->fetch();
    }

    /**
     * 新增或者修改
     * @return \think\response\Json
     * @author hutao
     */
    public function eduAdd()
    {
        if(request()->isAjax()){
            $edu = new EducationModel();
            $param = input('post.');
            if(empty($param['id'])){
                $param['owner'] = session('username');
                $param['edu_date'] = date("Y-m-d H:i:s");
                $flag = $edu->insertEdu($param);
            }else{
                $flag = $edu->editEdu($param);
            }
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function eduDel()
    {
        if(request()->isAjax()){
            $param = input('post.');
            $edu = new EducationModel();
            $flag = $edu->delEdu($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    public function eduDownload()
    {

    }

}