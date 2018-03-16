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
        $result = $this->field('biaoduan_name')->select();
        $str = "";

        //定义一个计数器，初始值为1
        $i = 1;
        $j = 1;
        $k = 1;
        $l = 1;
        $m = 1;
        $n = 1;
        $o = 1;
        $p = 1;
        $q = 1;

        //计算数组的长度
        $count = count($result);

        $str .= '{ "id": "' . '1' . '", "pId":"' . '0' . '", "name":"' . '机构设置'.'"';
        $str .= '},';
        $str .= '{ "id": "' . '11' . '", "pId":"' . '1' . '", "name":"' . '安全生产领导小组'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($i > $count){
                break;
            }
            $str .= '{ "id": "' . '11'. $i++ . '", "pId":"' . '11' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '12' . '", "pId":"' . '1' . '", "name":"' . '安全生产监督体系'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($j > $count){
                break;
            }
            $str .= '{ "id": "' . '12'. $j++ . '", "pId":"' . '12' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '13' . '", "pId":"' . '1' . '", "name":"' . '安全生产保证体系'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($k > $count){
                break;
            }
            $str .= '{ "id": "' . '13'. $k++ . '", "pId":"' . '13' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '14' . '", "pId":"' . '1' . '", "name":"' . '安全生产委员会'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($l > $count){
                break;
            }
            $str .= '{ "id": "' . '14'. $l++ . '", "pId":"' . '14' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '15' . '", "pId":"' . '1' . '", "name":"' . '反违章领导小组'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($m > $count){
                break;
            }
            $str .= '{ "id": "' . '15'. $m++ . '", "pId":"' . '15' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '16' . '", "pId":"' . '1' . '", "name":"' . '消防领导小组'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($n > $count){
                break;
            }
            $str .= '{ "id": "' . '16'. $n++ . '", "pId":"' . '16' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '17' . '", "pId":"' . '1' . '", "name":"' . '职业健康领导小组'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($o > $count){
                break;
            }
            $str .= '{ "id": "' . '17'. $o++ . '", "pId":"' . '17' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '18' . '", "pId":"' . '1' . '", "name":"' . '应急管理领导小组'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($p > $count){
                break;
            }
            $str .= '{ "id": "' . '18'. $p++ . '", "pId":"' . '18' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '2' . '", "pId":"' . '0' . '", "name":"' . '主要负责人及管理层职责'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '21' . '", "pId":"' . '2' . '", "name":"' . '金寨抽水蓄能电站'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($q > $count){
                break;
            }
            $str .= '{ "id": "' . '21'. $q++ . '", "pId":"' . '21' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }


        return "[" . substr($str, 0, -1) . "]";
    }



}