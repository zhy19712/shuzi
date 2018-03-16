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
        }
        catch (Exception $e)
        {
            $item=null;
        }
        if (!empty($item)) {
            return $item['id'];
        }
        $item = ['user_id' => $userId];
        return $this->insertGetId($item);
    }
}