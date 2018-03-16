<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/16
 * Time: 15:22
 */
namespace app\safety\model;
use think\Model;

class RiskDoubleDutyInfoModel extends Model
{
    protected $name='safety_riskdoubleduty_info';

    public function duty()
    {
        $this->hasOne('RiskDoubleDutyModel','duty_id');
    }

    public function add($info)
    {
        return $this->save($info);
    }
}