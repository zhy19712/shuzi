<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 13:56
 */
//机构和职责一级二级分类节点的设置
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;
class ResponsibilityinstyModel extends Model
{
    protected $name = 'contract';

    /**
     * [getNodeInfo 获取工程划分3级节点树结构数据]
     *
     */
    public function getNodeInfo()
    {
        $result = $this->field('id,biaoduan_name')->select();
        $str = "";

        foreach($result as $key=>$vo){
//            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "biaoduan_name":"' . $vo['biaoduan_name'].'"';

            $str .= '{ "id": "' . $vo['id'] . '","biaoduan_name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }



}