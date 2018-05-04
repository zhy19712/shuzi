<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/5/4
 * Time: 10:59
 */

namespace app\quality\controller;


use app\admin\controller\Base;
use app\quality\model\MaterialfileModel;

/**
 * 试验资料
 * Class Materialfile
 * @package app\quality\controller
 */
class Materialfile extends Base
{
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 获取一条编辑数据信息
     * @return \think\response\Json
     * @author hutao
     */
    public function getOne()
    {
        if(request()->isAjax()){
            $sdi = new MaterialfileModel();
            $data = $sdi->getAllOne(input('param.major_key'));
            return json($data);
        }
    }

    /**
     * 无文件上传编辑
     * @return \think\response\Json
     * @author hutao
     */
    public function edit()
    {
        if(request()->isAjax()){
            $sdi = new MaterialfileModel();
            $param = input('post.');
            $is_exist = $sdi->getOne($param['major_key']);
            if(empty($is_exist)){
                return json(['code' => '-1', 'msg' => '不存在的编号，请刷新当前页面']);
            }
            $data = [];
            // 前台提交的数据
            $data[0]['id'] = request()->param('major_key'); // 可选 文件自增编号 新增时 可以不必传 注意 修改的时候一定要传
            // 数组
            $entrustment_number = request()->param('entrustment_number/a'); //  委托编号
            $report_number = request()->param('report_number/a'); //  报告编号
            $approach_detection_time = request()->param('approach_detection_time/a'); //  进场检测时间/成型日期
            $using_position = request()->param('using_position/a'); //  使用部位/工程部位/检测部位
            $manufacturer = request()->param('manufacturer/a'); //  生产厂家
            $specifications = request()->param('specifications/a'); //  品种/标号/规格/等级/种类
            $lot_number = request()->param('lot_number/a'); //  批号/炉号/型号/桩号/母材批号
            $number_of_delegates = request()->param('number_of_delegates/a'); // 代表数量/进场数量
            $conclusion = request()->param('conclusion/a'); // 结论

            $kind = request()->param('kind/a'); // 样品名称/检测项目
            $bids = request()->param('bids/a'); //  标段

            // 单个值
            $broken_date = request()->param('broken_date'); // 破型日期
            $altitude = request()->param('altitude'); //  高程
            $design_strength_grade = request()->param('design_strength_grade'); //  设计强度等级
            $age = request()->param('age'); //  期龄
            $compression_strength = request()->param('compression_strength'); //  抗压强度
            $bar_diameter = request()->param('bar_diameter'); //  钢筋直径
            $status = request()->param('status'); //  状态
            $welding = request()->param('welding'); //  焊接或连接方式
            $order_number = request()->param('order_number'); //  序号

            // 水泥,粉煤灰,钢筋,粗骨料,细骨料,减水剂,速凝剂,引气剂,微膨胀剂,纤维,钢止水,橡胶止水,PVC止水,钢绞线,土工布
            for($i = 0; $i < 15;$i++){
                // 委托编号、报告编号、进场检测时间、使用部位、生产厂家
                $data[$i]['entrustment_number'] = $entrustment_number[$i];
                $data[$i]['report_number'] = $report_number[$i];
                $data[$i]['approach_detection_time'] = $approach_detection_time[$i];
                $data[$i]['using_position'] = $using_position[$i];
                $data[$i]['manufacturer'] = $manufacturer[$i];
                if(in_array($i,[0,1,3,4,5,6,7,8,9,10,11,12,13])){
                    $data[$i]['manufacturer'] = $specifications[$i]; // 品种/标号/规格/等级/种类
                }
                if(in_array($i,[0,1,2,5,6,7,8,9,10,11,12,13])){
                    $data[$i]['lot_number'] = $lot_number[$i]; // 批号/炉号/型号/桩号/母材批号
                }
                $data[$i]['number_of_delegates'] = $number_of_delegates[$i]; // 代表数量/进场数量
                $data[$i]['$conclusion'] = $conclusion[$i]; // 结论
            }
            //  混凝土 ==>委托编号、报告编号、成型日期、破型日期、标段、工程部位、桩号、高程、种类、设计强度等级、龄期(d)、抗压强度(Mpa)、结论
            $data[14]['entrustment_number'] = $entrustment_number[14];
            $data[14]['report_number'] = $report_number[14];
            $data[14]['approach_detection_time'] = $approach_detection_time[14];
            $data[14]['broken_date'] = $broken_date;
            $data[14]['bids'] = $bids[0];
            $data[14]['using_position'] = $using_position[14];
            $data[14]['lot_number'] = $lot_number[14];
            $data[14]['altitude'] = $altitude;
            $data[14]['specifications'] = $specifications[14];
            $data[14]['design_strength_grade'] = $design_strength_grade;
            $data[14]['age'] = $age;
            $data[14]['compression_strength'] = $compression_strength;
            $data[14]['conclusion'] = $conclusion[14];

            // 钢筋接头==> 检测日期、标段、工程部位、品种规格、钢筋直径、代表数量(个)、结论
            $data[15]['approach_detection_time'] = $approach_detection_time[15];
            $data[15]['bids'] = $bids[1];
            $data[15]['using_position'] = $using_position[15];
            $data[15]['specifications'] = $specifications[15];
            $data[15]['bar_diameter'] = $bar_diameter;
            $data[15]['number_of_delegates'] = $number_of_delegates[14];
            $data[15]['conclusion'] = $conclusion[15];
            // 止水材料接头==> 委托编号、报告编号、检测日期、标段、工程部位、母材批号、状态、焊接或连接方式、结论
            $data[16]['entrustment_number'] = $entrustment_number[15];
            $data[16]['report_number'] = $report_number[15];
            $data[16]['approach_detection_time'] = $approach_detection_time[16];
            $data[16]['bids'] = $bids[2];
            $data[16]['using_position'] = $using_position[16];
            $data[16]['lot_number'] = $lot_number[15];
            $data[16]['status'] = $status;
            $data[16]['welding'] = $welding;
            $data[16]['conclusion'] = $conclusion[16];
            // 压实度==> 委托编号、报告编号、检测日期、标段、检测部位、样品名称、结论
            $data[17]['entrustment_number'] = $entrustment_number[16];
            $data[17]['report_number'] = $report_number[16];
            $data[17]['approach_detection_time'] = $approach_detection_time[17];
            $data[17]['bids'] = $bids[3];
            $data[17]['using_position'] = $using_position[17];
            $data[17]['kind'] = $kind[0];
            $data[17]['conclusion'] = $conclusion[17];
            // 地基承载力==> 序号、委托编号、报告编号、检测日期、标段、检测部位、检测项目、结论
            $data[18]['order_number'] = $order_number;
            $data[18]['entrustment_number'] = $entrustment_number[17];
            $data[18]['report_number'] = $report_number[17];
            $data[18]['approach_detection_time'] = $approach_detection_time[18];
            $data[18]['bids'] = $bids[4];
            $data[18]['using_position'] = $using_position[18];
            $data[18]['kind'] = $kind[1];
            $data[18]['conclusion'] = $conclusion[1];
            $flag = $sdi->editMater($data);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }

    /**
     * 下载
     * @return \think\response\Json
     * @author hutao
     */
    public function download()
    {
        $major_key = input('param.major_key');
        $sdi = new MaterialfileModel();
        $param = $sdi->getOne($major_key);
        $filePath = $param['path'];
        if(!file_exists($filePath)){
            return json(['code' => '-1','msg' => '文件不存在']);
        }else if(request()->isAjax()){
            return json(['code' => 1]); // 文件存在，告诉前台可以执行下载
        }else{
            $fileName = $param['filename'];
            $file = fopen($filePath, "r"); //   打开文件
            //输入文件标签
            $fileName = iconv("utf-8","gb2312",$fileName);
            Header("Content-type:application/octet-stream ");
            Header("Accept-Ranges:bytes ");
            Header("Accept-Length:   " . filesize($filePath));
            Header("Content-Disposition:   attachment;   filename= " . $fileName);
            //   输出文件内容
            echo fread($file, filesize($filePath));
            fclose($file);
            exit;
        }
    }

    /**
     * 删除
     * @return \think\response\Json
     * @author hutao
     */
    public function del()
    {
        if(request()->isAjax()) {
            $sdi = new MaterialfileModel();
            $param = input('param.');
            $flag = $sdi->delMater($param['major_key']);
            return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
        }
    }


}