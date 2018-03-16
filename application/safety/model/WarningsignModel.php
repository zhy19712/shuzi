<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/16
 * Time: 10:40
 */
//警示标志
namespace app\safety\model;

use think\exception\PDOException;
use think\Model;

class WarningsignModel extends Model
{
    protected $name = 'safety_warningsign';

    /**
     * [getNodeInfo 获取工程划分2级节点树结构数据]
     *
     */
    public function getNodeInfo()
    {
        //定义一个空的字符串
        $str = "";

        $str .= '{ "id": "' . '1' . '", "pId":"' . '0' . '", "name":"' . '警示标志'.'"';
        $str .= '},';
        $str .= '{ "id": "' . '11' . '", "pId":"' . '1' . '", "name":"' . '分类列表树'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '111' . '", "pId":"' . '11' . '", "name":"' . '标识牌'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '112' . '", "pId":"' . '11' . '", "name":"' . '指路牌'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '113' . '", "pId":"' . '11' . '", "name":"' . '警示牌'.'"';
        $str .= '},';

        $str .= '{ "id": "' . '114' . '", "pId":"' . '11' . '", "name":"' . '防护围栏'.'"';
        $str .= '},';

        return "[" . substr($str, 0, -1) . "]";

    }



}