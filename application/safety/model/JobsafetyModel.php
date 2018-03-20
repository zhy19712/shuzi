<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 18:26
 */
//现场管理，作业安全左边的树状结构
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class JobsafetyModel extends Model
{
    protected  $name = 'contract';

    /*
     *获取作业安全左边的树状结构
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
        //计算数组的长度
        $count = count($result);

        $str .= '{ "id": "' . '1' . '", "pId":"' . '0' . '", "name":"' . '作业环境和作业条件'.'"';
        $str .= '},';
        $str .= '{ "id": "' . '11' . '", "pId":"' . '1' . '", "name":"' . '特种作业人员管理'.'"';
        $str .= '},';
        foreach((array)$result as $key=>$vo){

            if($i > $count){
                break;
            }
            $str .= '{ "id": "' . '11'. $i++ . '", "pId":"' . '11' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '12' . '", "pId":"' . '1' . '", "name":"' . '交叉作业管理'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($j > $count){
                break;
            }
            $str .= '{ "id": "' . '12'. $j++ . '", "pId":"' . '12' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '13' . '", "pId":"' . '1' . '", "name":"' . '危险化学品管理'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($k > $count){
                break;
            }
            $str .= '{ "id": "' . '13'. $k++ . '", "pId":"' . '13' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }

        $str .= '{ "id": "' . '14' . '", "pId":"' . '1' . '", "name":"' . '消防安全管理'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($l > $count){
                break;
            }
            $str .= '{ "id": "' . '14'. $l++ . '", "pId":"' . '14' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }


        $str .= '{ "id": "' . '2' . '", "pId":"' . '0' . '", "name":"' . '作业行为'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '21' . '", "pId":"' . '2' . '", "name":"' . '反违章记录'.'"';
        $str .= '},';

        foreach((array)$result as $key=>$vo){

            if($m > $count){
                break;
            }
            $str .= '{ "id": "' . '21'. $m++ . '", "pId":"' . '21' . '", "name":"' . $vo['biaoduan_name'].'"';

            $str .= '},';

        }


        return "[" . substr($str, 0, -1) . "]";
    }
}