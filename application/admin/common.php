<?php
use think\Db;


//验收预警，计算超期天数
function acceptanceWarning()
{
    sleep(0.5);
    $projects = Db::name('project')->order('id asc')->select();
    foreach($projects as $project){
        if(empty($project['pingding_date'])){
            $day1 = $project['wangong_date'];
            $day2 = date("Y-m-d");
            $diff = diffBetweenTwoDays($day1, $day2);
            if($project['cate'] == '开挖'){
                $limit = 7;
            }else if( $project['cate'] == '支护'){
                $limit = 28;
            }else if( $project['cate'] == '混凝土'){
                $limit = 28;
            }
            $status['exceed'] =$diff - $limit;
            if($status['exceed']>0){
                $status['status'] = '预警中';
            }else{
                $status['status'] = '';
            }
            Db::name('project')->where('id=' . $project['id'])->update($status);
        }else{
            if($project['exceed'] > 0){
                $status['status'] = '已处理';
                Db::name('project')->where('id=' . $project['id'])->update($status);
            }
        }
    }
}

/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays ($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);

    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    return ($second1 - $second2) / 86400;
}

//$day1 = "2013-07-27";
//$day2 = "2013-08-04";
//$diff = diffBetweenTwoDays($day1, $day2);



/**
 * 将字符解析成数组
 * @param $str
 */
function parseParams($str)
{
    $arrParams = [];
    parse_str(html_entity_decode(urldecode($str)), $arrParams);
    return $arrParams;
}


/**
 * 子孙树 用于功能整理
 * @param $param
 * @param int $pid
 */
function subTree($param, $pid = 0)
{
    static $res = [];
    foreach($param as $key=>$vo){

        if( $pid == $vo['pid'] ){
            $res[] = $vo;
            subTree($param, $vo['id']);
        }
    }

    return $res;
}


/**
 * 记录日志
 * @param  [type] $uid         [用户id]
 * @param  [type] $username    [用户名]
 * @param  [type] $description [描述]
 * @param  [type] $status      [状态]
 * @return [type]              [description]
 */
function writelog($uid,$username,$description,$status)
{

    $data['admin_id'] = $uid;
    $data['admin_name'] = $username;
    $data['description'] = $description;
    $data['status'] = $status;
    $data['ip'] = request()->ip();
    $data['add_time'] = time();
    $log = Db::name('Log')->insert($data);

}


/**
 * 整理功能树方法
 * @param $param
 * @return array
 */
function prepareMenu($param)
{
    $parent = []; //父类
    $child = [];  //子类

    foreach($param as $key=>$vo){

        if($vo['pid'] == 0){
            $vo['href'] = '#';
            $parent[] = $vo;
        }else{
            $vo['href'] = url($vo['name']); //跳转地址
            $child[] = $vo;
        }
    }

    foreach($parent as $key=>$vo){
        foreach($child as $k=>$v){

            if($v['pid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
            }
        }
    }
    unset($child);
    return $parent;
}


/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return $size . $delimiter . $units[$i];
}
