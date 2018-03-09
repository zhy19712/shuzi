<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/8
 * Time: 17:55
 */
//设备设施建设
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;
class EquipmentModel extends Model
{
    protected  $name = 'contract';

    /*
      *获取设备设施建设左边的树状结构
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
        //计算数组的长度
        $count = count($result);

        $str .= '{ "id": "' . '1' . '", "pId":"' . '0' . '", "name":"' . '相关方设备设施管理'.'"';
        $str .= '},';
        $str .= '{ "id": "' . '11' . '", "pId":"' . '1' . '", "name":"' . '设备设施建设'.'"';
        $str .= '},';
        foreach((array)$result as $key=>$vo){

            if($i > $count){
                break;
            }
            $str .= '{ "id": "' . '11'. $i++ . '", "pId":"' . '11' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '12' . '", "pId":"' . '1' . '", "name":"' . '设备设施验收'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '121' . '", "pId":"' . '12' . '", "name":"' . '临建设施验收'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($j > $count){
                break;
            }
            $str .= '{ "id": "' . '121'. $j++ . '", "pId":"' . '121' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '122' . '", "pId":"' . '12' . '", "name":"' . '进场设备验收'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($k > $count){
                break;
            }
            $str .= '{ "id": "' . '122'. $k++ . '", "pId":"' . '122' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '123' . '", "pId":"' . '12' . '", "name":"' . '自制设备验收'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($l > $count){
                break;
            }
            $str .= '{ "id": "' . '123'. $l++ . '", "pId":"' . '123' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '124' . '", "pId":"' . '12' . '", "name":"' . '脚手架验收'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($m > $count){
                break;
            }
            $str .= '{ "id": "' . '124'. $m++ . '", "pId":"' . '124' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '13' . '", "pId":"' . '1' . '", "name":"' . '设备设施运行管理'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '131' . '", "pId":"' . '13' . '", "name":"' . '特种设备'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($n > $count){
                break;
            }
            $str .= '{ "id": "' . '131'. $n++ . '", "pId":"' . '131' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '132' . '", "pId":"' . '13' . '", "name":"' . '交通车辆'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($o > $count){
                break;
            }
            $str .= '{ "id": "' . '132'. $o++ . '", "pId":"' . '132' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '133' . '", "pId":"' . '13' . '", "name":"' . '安全工器具'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($p > $count){
                break;
            }
            $str .= '{ "id": "' . '133'. $p++ . '", "pId":"' . '133' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '2' . '", "pId":"' . '0' . '", "name":"' . '内部设备设施管理'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '21' . '", "pId":"' . '2' . '", "name":"' . '车辆管理'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '22' . '", "pId":"' . '2' . '", "name":"' . '设备管理'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '23' . '", "pId":"' . '2' . '", "name":"' . '安全工器具管理'.'"';
        $str .= '},';

        return "[" . substr($str, 0, -1) . "]";
    }
}