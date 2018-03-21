<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/19
 * Time: 15:16
 */

namespace app\safety\model;

use think\Model;

class RiskImgModel extends Model
{
    protected $name = 'safety_risk_img';
    public function risk()
    {
       return $this->belongsTo('Risk');
    }
}