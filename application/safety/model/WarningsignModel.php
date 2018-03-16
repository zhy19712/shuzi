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

    /*
     * 添加新的警示标志
    */
    public function insertWarningsign($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 编辑警示标志
    */
    public function editWarningsign($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 删除警示标志
    */
    public function delWarningsign($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
     * 获取一条警示标志记录
    */
    /**
     * @param $id
     * @return array|false|\PDOStatement|string|Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }





}