<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/16
 * Time: 15:22
 */

namespace app\safety\model;

use think\Db;
use think\Exception;
use think\Model;

class RiskDoubleDutyModel extends Model
{
    protected $name = 'safety_riskdoubleduty';

    /**
     * @param $user
     * @return int|string
     */
    public function getbyid($userId)
    {
        try {
            $item = $this->where('user_id', $userId)->find();
        } catch (Exception $e) {
            $item = null;
        }
        if (!empty($item)) {
            return $item;
        }
        $item = ['user_id' => $userId];
        $id = $this->insertGetId($item);
        return $this->where('user_id', $id)->find();
    }

    public function prossScore($userId,$score,$cat,$context,$time)
    {
        $item = $this->getbyid($userId);

        Db::transaction();
        try
        {
            //插入增减分记录
            $info=new RiskDoubleDutyInfoModel();
            $info->insert(['score'=>$score,'context'=>$context,'cat'=>$cat,'date'=>$time]);

            $item['score']+=$item['score'];
            $item->save();
            return true;
        }
        catch (Exception $e)
        {
            Db::rollback();
            return false;
        }
    }
}