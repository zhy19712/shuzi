<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/14
 * Time: 11:50
 */
//现场管理->工作健康
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class JobhealthModel extends Model
{
    protected $name = 'contract';

    /**
     * [getNodeInfo 获取职业健康2级节点树结构数据]
     *
     */
    public function getNodeInfo()
    {
        $result = $this->field('biaoduan_name')->select();
        $str = "";
        //定义一个计数器，初始值为1
        $i = 1;

        //计算数组的长度
        $count = count($result);

        $str .= '{ "id": "' . '1' . '", "pId":"' . '0' . '", "name":"' . '职业健康管理'.'"';
        $str .= '},';
        $str .= '{ "id": "' . '11' . '", "pId":"' . '1' . '", "name":"' . '相关方'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($i > $count){
                break;
            }
            $str .= '{ "id": "' . '11'. $i++ . '", "pId":"' . '11' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '12' . '", "pId":"' . '1' . '", "name":"' . '监理部'.'"';
        $str .= '},';

        return "[" . substr($str, 0, -1) . "]";

    }
}