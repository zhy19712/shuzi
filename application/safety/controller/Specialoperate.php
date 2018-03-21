<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/21
 * Time: 17:20
 */
//特种作业人员管理
namespace app\safety\controller;

use app\admin\controller\Base;
use app\safety\model\SpecialoperateModel;
use think\Db;
use think\Loader;

class Specialoperate extends Base
{
    /*
     * 获取一条特种作业人员管理信息
     */
    public function getindex()
    {
        if(request()->isAjax()){
            $special = new SpecialoperateModel();
            $param = input('post.');
            $data = $special->getOne($param['id']);

            $data['filename'] = explode("☆",$data['filename']);//拆解拼接的文件、图片名
            $data['path'] = explode("☆",$data['path']);//拆解拼接的文件、图片名

            return json(['code'=> 1, 'data' => $data]);
        }
        return $this->fetch();
    }

    /*
     * 新增/编辑一条消防安全管理信息
     */
    public function  specialoperateEdit()
    {
        $special = new SpecialoperateModel();

        $param = input('post.');

        $pathImgName = input('post.pathImgName/a');//获取post传过来的多个文件、图片的名字，包含在一个一维数组中。
        $pathImgArr = input('post.pathImgArr/a');//获取post传过来的多个文件、图片的路径，包含在一个一维数组中。
        $pathImgDel = input('post.pathImgDel/a');//获取post传过来要删除的多个文件、图片的路径，包含在一个一维数组中。

        //循环删除文件、图片
        foreach((array)$pathImgDel as $v)
        {
            unlink($v);
        }

        if(request()->isAjax()){

            if(empty($param['id']))//id为空时表示新增
            {
                $data = [
//                    'id' => $param['id'],
                    'selfid' => $param['selfid'],//区别类别
                    'name' => $param['name'],//姓名
                    'job_name' => $param['job_name'],//分包单位/作业队
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'special_type_work' => $param['special_type_work'],//特殊工种
                    'job_certificate' => $param['job_certificate'],//职业资格证书名称
                    'card_number' => $param['card_number'],//身份证号
                    'issuing_unit' => $param['issuing_unit'],//发证单位
                    'date_evidence' => $param['date_evidence'],//取证日期
                    'effective_date' => $param['effective_date'],//有效日期
                    'certificate_number' => $param['certificate_number'],//证书编号
                    'advance_retreat_time' => $param['advance_retreat_time'],//进退场时间
                    'document_status' => $param['document_status'],//证件状态

                    'filename' => implode("☆",$pathImgName),//上传所有文件图片的拼接名
                    'path' => implode("☆",$pathImgArr),//上传所有文件、图片拼接路径

                    'remark' => $param['remark'],//备注
                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $special->insertSpecialoperate($data);


                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }else {


                $data = [
                    'id' => $param['id'],
//                    'selfid' => $param['selfid'],//区别类别
                    'name' => $param['name'],//姓名
                    'job_name' => $param['job_name'],//分包单位/作业队
                    'sex' => $param['sex'],//性别
                    'age' => $param['age'],//年龄
                    'special_type_work' => $param['special_type_work'],//特殊工种
                    'job_certificate' => $param['job_certificate'],//职业资格证书名称
                    'card_number' => $param['card_number'],//身份证号
                    'issuing_unit' => $param['issuing_unit'],//发证单位
                    'date_evidence' => $param['date_evidence'],//取证日期
                    'effective_date' => $param['effective_date'],//有效日期
                    'certificate_number' => $param['certificate_number'],//证书编号
                    'advance_retreat_time' => $param['advance_retreat_time'],//进退场时间
                    'document_status' => $param['document_status'],//证件状态

                    'filename' => implode("*",$pathImgName),//上传所有文件图片的拼接名
                    'path' => implode("*",$pathImgArr),//上传所有文件、图片拼接路径

                    'remark' => $param['remark'],//备注
//                    'date' => date("Y-m-d H:i:s")//添加时间
                ];
                $flag = $special->editSpecialoperate($data);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
    }

    /*
     * 删除一条特种作业人员管理信息
     */
    public function delSpecialoperateDel()
    {
        $special = new SpecialoperateModel();
        if(request()->isAjax()) {
            $param = input('post.');
            $data = $special->getOne($param['id']);
            $path = explode("☆",$data['path']);
            foreach ((array)$path as $v)
            {
                unlink($v); //删除文件、图片
            }
            $flag = $special->delSpecialoperate($param['id']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }
}