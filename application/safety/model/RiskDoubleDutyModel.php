<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/16
 * Time: 15:22
 */

namespace app\safety\model;



use app\admin\model\UserModel;
use think\Db;
use think\Exception;
use think\Model;

class RiskDoubleDutyModel extends Model
{
    protected $name = 'safety_riskdoubleduty';

    public function infos()
    {
        return $this->hasMany('RiskDoubleDutyInfoModel','duty_id','id');
    }

    /**
     * @param $id
     */
    public function getOne($id)
    {
        return $this->where('id',$id)->find();
    }

    /**
     * @param $user
     * @return int|string
     */
    public function getbyusername($user)
    {
        try {
            $item = $this->where('user', $user)->find();
        } catch (Exception $e) {
            $item = null;
        }
        if (!empty($item)) {
            return $item;
        }
        try {
            $m = UserModel::where('username', $user)->find();
        }catch (Exception $e)
        {
            return false;
        }
        $item = ['user' =>$m['username'],'dep'=>$m->depName['title']];
        $id = $this->insertGetId($item);
        return $this->where('id', $id)->find();
    }

    public function prossScore($user,$score,$cat,$context,$time)
    {
        $item = $this->getbyusername($user);
        if ($item)
        {
            return false;
        }
        Db::transaction();
        try
        {
            //插入增减分记录
            $info=new RiskDoubleDutyInfoModel();
            $info->insert(['score'=>$score,'context'=>$context,'cat'=>$cat,'date'=>$time,'duty_id'=>$item['id']]);

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