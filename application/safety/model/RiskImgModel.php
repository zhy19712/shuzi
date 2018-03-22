<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/19
 * Time: 15:16
 */

namespace app\safety\model;

use think\Exception;
use think\Model;

class RiskImgModel extends Model
{
    protected $name = 'safety_risk_img';
    public function risk()
    {
       return $this->belongsTo('Risk');
    }

    /**
     * 删除文件
     * @param null $url
     */
    public function delImg($url=null)
    {
        try{
        RiskImgModel::where('path',$url)->delete();
        unlink($url);}
        catch (Exception $e)
        {

        }
    }
}