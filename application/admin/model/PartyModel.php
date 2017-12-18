<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/12/18
 * Time: 9:30
 */

namespace app\admin\model;
use think\Model;
use think\Db;


class PartyModel extends Model
{
    protected  $name = 'party';
    /**
     * 获取甲方信息给下拉框
     * @param $party
     */
    public function getFirstParties()
    {
        return $this->where('party','=',1)->select();
    }

    /**
     * 获取乙方信息给下拉框
     * @param $party
     */
    public function getSecondParties()
    {
        return $this->where('party','=',2)->select();
    }


}