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
            $data['id'] = request()->param('major_key'); // 可选 文件自增编号 新增时 可以不必传 注意 修改的时候一定要传
            // 数组
            $entrustment_number_1 = request()->param('entrustment_number'); //  委托编号
            $entrustment_number = explode(',',$entrustment_number_1);
            $report_number_1 = request()->param('report_number'); //  报告编号
            $report_number = explode(',',$report_number_1);
            $approach_detection_time_1 = request()->param('approach_detection_time'); //  进场检测时间/成型日期
            $approach_detection_time = explode(',',$approach_detection_time_1);
            $using_position_1 = request()->param('using_position'); //  使用部位/工程部位/检测部位
            $using_position = explode(',',$using_position_1);
            $manufacturer_1 = request()->param('manufacturer'); //  生产厂家
            $manufacturer = explode(',',$manufacturer_1);
            $specifications_1 = request()->param('specifications'); //  品种/标号/规格/等级/种类
            $specifications = explode(',',$specifications_1);
            $lot_number_1 = request()->param('lot_number'); //  批号/炉号/型号/桩号/母材批号
            $lot_number = explode(',',$lot_number_1);
            $number_of_delegates_1 = request()->param('number_of_delegates'); // 代表数量/进场数量
            $number_of_delegates = explode(',',$number_of_delegates_1);
            $conclusion_1 = request()->param('conclusion'); // 结论
            $conclusion = explode(',',$conclusion_1);

            $kind_1 = request()->param('kind'); // 样品名称/检测项目
            $kind = explode(',',$kind_1);
            $bids_1 = request()->param('bids'); //  标段
            $bids = explode(',',$bids_1);

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

            $en = $ma = $sp = $lot = $del = 0;
            for($i = 0; $i < 20;$i++){
                // 委托编号、报告编号、进场检测时间
                if($i != 16){
                    $data[$i]['entrustment_number'] = $entrustment_number[$en];
                    $data[$i]['report_number'] = $report_number[$en];
                    $en++;
                }
                $data[$i]['approach_detection_time'] = $approach_detection_time[$i];
                $data[$i]['using_position'] = $using_position[$i]; // 使用部位
                if($i < 15){
                    $data[$i]['manufacturer'] = $manufacturer[$ma]; // 生产厂家
                    $ma++;
                }
                if(!in_array($i,[2,14,17,18,19])){
                    $data[$i]['specifications'] = $specifications[$sp]; // 品种/标号/规格/等级/种类
                    $sp++;
                }
                if(!in_array($i,[3,4,16,18,19])){
                    $data[$i]['lot_number'] = $lot_number[$lot]; // 批号/炉号/型号/桩号/母材批号
                    $lot++;
                }
                if(!in_array($i,[15,17,18,19])){
                    $data[$i]['number_of_delegates'] = $number_of_delegates[$del]; // 代表数量/进场数量
                    $del++;
                }
                $data[$i]['conclusion'] = $conclusion[$i]; // 结论
            }

            //  混凝土 ==>委托编号、报告编号、成型日期、破型日期、标段、工程部位、桩号、高程、种类、设计强度等级、龄期(d)、抗压强度(Mpa)、结论
            $data[15]['broken_date'] = $broken_date;
            $data[15]['bids'] = $bids[0];
            $data[15]['altitude'] = $altitude;
            $data[15]['design_strength_grade'] = $design_strength_grade;
            $data[15]['age'] = $age;
            $data[15]['compression_strength'] = $compression_strength;
            // 钢筋接头==> 检测日期、标段、工程部位、品种规格、钢筋直径、代表数量(个)、结论
            $data[16]['bids'] = $bids[1];
            $data[16]['bar_diameter'] = $bar_diameter;
            // 止水材料接头==> 委托编号、报告编号、检测日期、标段、工程部位、母材批号、状态、焊接或连接方式、结论
            $data[17]['bids'] = $bids[2];
            $data[17]['status'] = $status;
            $data[17]['welding'] = $welding;
            // 压实度==> 委托编号、报告编号、检测日期、标段、检测部位、样品名称、结论
            $data[18]['bids'] = $bids[3];
            $data[18]['kind'] = $kind[0];
            // 地基承载力==> 序号、委托编号、报告编号、检测日期、标段、检测部位、检测项目、结论
            $data[19]['order_number'] = $order_number;
            $data[19]['bids'] = $bids[4];
            $data[19]['kind'] = $kind[1];
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