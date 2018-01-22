<?php
use think\Db;

//获取后缀名
function get_extension($file)
{
    substr(strrchr($file, '.'), 1);
}

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
            $destfilename,
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
