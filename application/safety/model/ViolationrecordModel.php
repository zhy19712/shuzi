<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/20
 * Time: 18:54
 */
//作业安全，反违章记录
namespace app\safety\model;
use think\exception\PDOException;
use think\Model;

class ViolationrecordModel extends Model
{
    protected $name = 'safety_violation_record';
}