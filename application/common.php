<?php
use think\Db;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;

//获取后缀名
function get_extension($file)
{
    return substr(strrchr($file, '.'), 1);
}

//调用MS office DCOM 将文件转换为pdf， 使用pdf.js预览， 要求服务器安装MS office 较高版本，Linux环境下需改用LebreOffice或openOffice
function ppt_to_pdf($path) {
    $srcfilename = 'D:/phpStudy/WWW/shuzi/public' . substr($path, 1);
    $destfilename = 'D:/phpStudy/WWW/shuzi/public/uploads/temp/' . basename($path);
    try {
        if(!file_exists($srcfilename)){
            return json(['code' => 0, 'msg' => '文件不存在']);
        }
        $ppt = new \COM("powerpoint.application") or die("Unable to instantiate Powerpoint");
        $presentation = $ppt->Presentations->Open($srcfilename, false, false, false);
        if(file_exists($destfilename . '.pdf')){
            unlink($destfilename . '.pdf');
        }
        $presentation->SaveAs($destfilename,32,1);
        $presentation->Close();
        $ppt->Quit();
    } catch (\Exception $e) {
        if (method_exists($ppt, "Quit")){
            $ppt->Quit();
        }
        return json(['code' => 0, 'msg' => '未知错误']);
    }
}

function excel_to_pdf($path) {
    $srcfilename = 'D:/phpStudy/WWW/shuzi/public' . substr($path, 1);
    $destfilename = 'D:/phpStudy/WWW/shuzi/public/uploads/temp/' . basename($path);
    try {
        if(!file_exists($srcfilename)){
            return json(['code' => 0, 'msg' => '文件不存在']);
        }
        $excel = new \COM("excel.application") or die("Unable to instantiate excel");
        $workbook = $excel->Workbooks->Open($srcfilename, null, false, null, "1", "1", true);
        if(file_exists($destfilename . '.pdf')){
            unlink($destfilename . '.pdf');
        }
        $workbook->ExportAsFixedFormat(0, $destfilename);
        $workbook->Close();
        $excel->Quit();
    } catch (\Exception $e) {
        echo ("src:$srcfilename catch exception:" . $e->__toString());
        if (method_exists($excel, "Quit")){
            $excel->Quit();
        }
        return json(['code' => 0, 'msg' => '未知错误']);
    }
}

function doc_to_pdf($path) {
    $srcfilename = 'D:/phpStudy/WWW/shuzi/public' . substr($path, 1);
    $destfilename = 'D:/phpStudy/WWW/shuzi/public/uploads/temp/' . basename($path);
    try {
        if(!file_exists($srcfilename)){
            return json(['code' => 0, 'msg' => '文件不存在']);
        }

        $word = new \COM("word.application") or die("Can't start Word!");
        $word->Visible=0;
        $word->Documents->Open($srcfilename, false, false, false, "1", "1", true);
        if(file_exists($destfilename . '.pdf')){
            unlink($destfilename . '.pdf');
        }

        $word->ActiveDocument->final = false;
        $word->ActiveDocument->Saved = true;
        $word->ActiveDocument->ExportAsFixedFormat(
            $destfilename . '.pdf',
            17,                         // wdExportFormatPDF
            false,                      // open file after export
            0,                          // wdExportOptimizeForPrint
            3,                          // wdExportFromTo
            1,                          // begin page
            5000,                       // end page
            7,                          // wdExportDocumentWithMarkup
            true,                       // IncludeDocProps
            true,                       // KeepIRM
            1                           // WdExportCreateBookmarks
        );
        $word->ActiveDocument->Close();
        $word->Quit();
    } catch (\Exception $e) {
        if (method_exists($word, "Quit")){
            $word->Quit();
        }
        return json(['code' => 0, 'msg' => '未知错误']);
    }
}

//上传权限判断
function uploadAccess()
{
    $auth = new \com\Auth();
    $module     = strtolower(request()->module());
    $controller = strtolower(request()->controller());
    $action     = strtolower(request()->action());
    $url        = $module."/".$controller."/".$action;
    if(session('uid')!=1){
        if(!$auth->check($url,session('uid'))){
            return json(['msg'=> '抱歉，您没有操作权限']);
        }
    }
}



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


/**
 * 字符串截取，支持中文和其他编码
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}



/**
 * 读取配置
 * @return array
 */
function load_config(){
    $list = Db::name('config')->select();
    $config = [];
    foreach ($list as $k => $v) {
        $config[trim($v['name'])]=$v['value'];
    }

    return $config;
}


/**
 * 验证手机号是否正确
 * @author honfei
 * @param number $mobile
 */
function isMobile($mobile) {
    if (!is_numeric($mobile)) {
        return false;
    }
    return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
}


/**
 * 阿里云云通信发送短息
 * @param string $mobile    接收手机号
 * @param string $tplCode   短信模板ID
 * @param array  $tplParam  短信内容
 * @return array
 */
function sendMsg($mobile,$tplCode,$tplParam){
    if( empty($mobile) || empty($tplCode) ) return array('Message'=>'缺少参数','Code'=>'Error');
    if(!isMobile($mobile)) return array('Message'=>'无效的手机号','Code'=>'Error');

    require_once '../extend/aliyunsms/vendor/autoload.php';
    Config::load();             //加载区域结点配置   
    $accessKeyId = config('alisms_appkey');
    $accessKeySecret = config('alisms_appsecret');
    if( empty($accessKeyId) || empty($accessKeySecret) ) return array('Message'=>'请先在后台配置appkey和appsecret','Code'=>'Error');
    $templateParam = $tplParam; //模板变量替换  
    //$signName = (empty(config('alisms_signname'))?'阿里大于测试专用':config('alisms_signname'));  
    $signName = config('alisms_signname');
    //短信模板ID 
    $templateCode = $tplCode;
    //短信API产品名（短信产品名固定，无需修改）  
    $product = "Dysmsapi";
    //短信API产品域名（接口地址固定，无需修改）  
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）  
    $region = "cn-hangzhou";
    // 初始化用户Profile实例  
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    // 增加服务结点  
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    // 初始化AcsClient用于发起请求  
    $acsClient= new DefaultAcsClient($profile);
    // 初始化SendSmsRequest实例用于设置发送短信的参数  
    $request = new SendSmsRequest();
    // 必填，设置雉短信接收号码  
    $request->setPhoneNumbers($mobile);
    // 必填，设置签名名称  
    $request->setSignName($signName);
    // 必填，设置模板CODE  
    $request->setTemplateCode($templateCode);
    // 可选，设置模板参数     
    if($templateParam) {
        $request->setTemplateParam(json_encode($templateParam));
    }
    //发起访问请求  
    $acsResponse = $acsClient->getAcsResponse($request);
    //返回请求结果  
    $result = json_decode(json_encode($acsResponse),true);

    return $result;
}



//生成网址的二维码 返回图片地址
function Qrcode($token, $url, $size = 8){
    $md5 = md5($token);
    $dir = date('Ymd'). '/' . substr($md5, 0, 10) . '/';
    $patch = 'qrcode/' . $dir;
    if (!file_exists($patch)){
        mkdir($patch, 0755, true);
    }
    $file = 'qrcode/' . $dir . $md5 . '.png';
    $fileName =  $file;
    if (!file_exists($fileName)) {

        $level = 'L';
        $data = $url;
        QRcode::png($data, $fileName, $level, $size, 2, true);
    }
    return $file;
}



/**
 * 循环删除目录和文件
 * @param string $dir_name
 * @return bool
 */
function delete_dir_file($dir_name) {
    $result = false;
    if(is_dir($dir_name)){
        if ($handle = opendir($dir_name)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . DS . $item)) {
                        delete_dir_file($dir_name . DS . $item);
                    } else {
                        unlink($dir_name . DS . $item);
                    }
                }
            }
            closedir($handle);
            if (rmdir($dir_name)) {
                $result = true;
            }
        }
    }

    return $result;
}



//时间格式化1
function formatTime($time) {
    $now_time = time();
    $t = $now_time - $time;
    $mon = (int) ($t / (86400 * 30));
    if ($mon >= 1) {
        return '一个月前';
    }
    $day = (int) ($t / 86400);
    if ($day >= 1) {
        return $day . '天前';
    }
    $h = (int) ($t / 3600);
    if ($h >= 1) {
        return $h . '小时前';
    }
    $min = (int) ($t / 60);
    if ($min >= 1) {
        return $min . '分钟前';
    }
    return '刚刚';
}


//时间格式化2
function pincheTime($time) {
    $today  =  strtotime(date('Y-m-d')); //今天零点
    $here   =  (int)(($time - $today)/86400) ;
    if($here==1){
        return '明天';
    }
    if($here==2) {
        return '后天';
    }
    if($here>=3 && $here<7){
        return $here.'天后';
    }
    if($here>=7 && $here<30){
        return '一周后';
    }
    if($here>=30 && $here<365){
        return '一个月后';
    }
    if($here>=365){
        $r = (int)($here/365).'年后';
        return   $r;
    }
    return '今天';
}


function getRandomString($len, $chars=null){
    if (is_null($chars)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    }
    mt_srand(10000000*(double)microtime());
    for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
        $str .= $chars[mt_rand(0, $lc)];
    }
    return $str;
}


function random_str($length){
    //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
    $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

    $str = '';
    $arr_len = count($arr);
    for ($i = 0; $i < $length; $i++)
    {
        $rand = mt_rand(0, $arr_len-1);
        $str.=$arr[$rand];
    }

    return $str;
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
    $grandchild = [];

    foreach($param as $key=>$vo){

        if($vo['name'] == '#'){
            $vo['href'] = '#';
            $parent[] = $vo;
        }else if($vo['name'] == '##'){
            $vo['href'] = '#';
            $child[] = $vo;
        }
        else{
            $vo['href'] = url($vo['name']); //跳转地址
            $grandchild[] = $vo;
        }
    }

    foreach($parent as $key=>$vo){
        $i = 0;
        foreach($child as $k=>$v){
            if($v['pid'] == $vo['id']){
                $parent[$key]['child'][] = $v;
                foreach($grandchild as $kk=>$vv){
                    if($vv['pid'] == $v['id']){
                        $parent[$key]['child'][$i]['grandchild'][] = $vv;
                    }
                }
                $i++;
            }
        }
        foreach($grandchild as $kk=>$vv){
            if($vv['pid'] == $vo['id']){
                $parent[$key]['child'][] = $vv;
            }
        }
    }

    return $parent;
}