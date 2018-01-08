<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\DivideModel;
use app\admin\model\ProjectModel;
use think\Db;

use think\Loader;

class project extends Base
{
    public function index()
    {
        if(request()->isAjax()){
            $node = new DivideModel();
            $nodeStr = $node->getNodeInfo();
            return json($nodeStr);}
        else
            return $this->fetch();
    }


    /**
     * [projectAdd 单元工程验收批次添加信息(保存按钮)]
     */
    public function projectAdd()
    {
        $project = new ProjectModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {

                $flag = $project->insertProject($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $project->editProject($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
    }

    /**
     * [projectEdit 单元工程验收批次编辑]
     * @return [type] [description]
     */
    public function projectEdit()
    {
        $project = new ProjectModel();

        if(request()->isAjax()){

            $param = input('post.');
            $data = $project->getOneProject($param['id']);
            return json(['data' => $data, 'msg' => "success"]);
        }
    }

    /**
     * [projectDel 单元工程验收批次删除]
     * @return [type] [description]
     */
    public function projectDel()
    {
        $id = input('param.id');
        $project = new ProjectModel();
        $flag = $project->delProject($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }


    /**
     * [projectAdd 节点添加(保存按钮)]
     */
    public function nodeAdd()
    {
        $node = new DivideModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {

                $flag = $node->insertNode($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }
            else if(!empty($param['id']))
            {
                $flag = $node->editNode($param);
                return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
            }

        }
        return $this->fetch();
    }

    /**
     * [projectEdit 节点编辑]
     * @return [type] [description]
     */
    public function nodeEdit()
    {
        $node = new DivideModel();

        if(request()->isAjax()){

            $param = input('post.');
            $data = $node->getOneNode($param['id']);
            return json(['data' => $data, 'msg' => "success"]);
        }
    }

    /**
     * [projectDel 节点删除]
     * @return [type] [description]
     */
    public function nodeDel()
    {
        $id = input('param.id');
        $node = new DivideModel();

        $childList = $node->cateTree($id);
        foreach ($childList as $child){
           $node->delNode($child['id']);
        }
        $flag = $node->delNode($id);
        return json(['code' => $flag['code'], 'data' => $flag['data'], 'msg' => $flag['msg']]);
    }

    /**
     * [获取当前节点的所有父级]
     * @return [type] [description]
     */
    public function getParents()
    {
        $node = new DivideModel();
        $parent = array();
        $path = "";
        if(request()->isAjax()){
            $param = input('post.');
            $id = $param['id'];

            while($id>0)
            {
                $data = $node->getOneNode($id);
                array_unshift($parent, $data['id']);
                $path = $data['name'] . ">>" . $path;
                $id = $data['pid'];
                $data = array();
            }
            return json(['path' => substr($path, 0 , -2), 'idList' => $parent, 'msg' => "success"]);
        }
    }

    /**
     * [导入excel到数据库里]
     */
    public function importExcel(){
        $file = request()->file('excel');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/excel');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/excel' . DS . $exclePath;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 首先导入单位工程，根据单位工程设置根节点和二级节点
            $divide['pid'] = 0;
            $divide['name'] = $excel_array[0][0];
            // 插入前首先判断是否是 重复插入
            $bol = Db::name('project_divide')->where('name',$divide['name'])->value('name');
            if($bol){
                $json_data['status'] = 0;
                $json_data['info'] = '该表格已经提交过';
                return json($json_data);
            }
            $root_pid = Db::name('project_divide')->insertGetId($divide); // 插入根节点
            // 批量插入二级节点
            $data = [];
            foreach($excel_array as $k=>$v){
                if($k > 0 && !empty($v[3])){
                    $data[$k]['pid'] = $root_pid; // 根节点pid
                    $data[$k]['sn'] = $v[0]; // 单位工程编号
                    $data[$k]['name'] = $v[3]; // 单位工程名称
                    $data[$k]['primary'] = $v[4]; // 是否主要单位工程
                }
            }
            array_shift($data);  // 删除第一个数组(标题);
            $success = Db::name('project_divide')->insertAll($data);
            if(!$success){
                $json_data['status'] = 0;
                $json_data['info'] = '首页 单位工程 格式有误';
                return json($json_data);
            }
            // 到此步骤时，第一页 (单位工程) 数据已经导入完成 根节点和二级节点创建成功

            // 获取二级节点的自增编号做为三级节点的pid
            $second_pid = Db::name('project_divide')->where('pid',$root_pid)->field('id,sn,primary')->select();
            $second_pid_value = $second_pid_array = $second_primary_array = [];
            foreach ($second_pid as $pk=>$pv){
                $second_pid_value[] = $pv['id']; // 二级节点的编号
                // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                $second_pid_array[$pv['id']] = $pv['sn'];
                if(!empty($pv['primary'])){
                    $second_primary_array[$pv['sn']] = $pv['primary'];
                }
            }
            /**
             *  批量插入三级节点
             *  这里的页面 一般为 单位工程，分布工程，单元工程三大模块
             **/
            $page_num = $obj_PHPExcel->getSheetCount(); // 获取excel一共有几页
            $row_array = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[2]) && !empty($page_v[3])){ // 前几行都是标题
                        if(in_array($page_v[0],$second_pid_array)){
                           $current_pid =  array_search($page_v[0],$second_pid_array);
                           $current_primary =  array_key_exists($page_v[0],$second_primary_array) ? '是' : '';
                        }
                        $row_array[$i][$pk]['pid'] = $current_pid; // 上级单位工程编号
                        $row_array[$i][$pk]['sn'] = $page_v[2]; // 分部工程编号
                        $row_array[$i][$pk]['name'] = $page_v[3]; // 分部工程名称
                        $row_array[$i][$pk]['primary'] = $current_primary; // 是否主要分部工程 继承上级
                    }
                }
            }
            $new_row_array = [];
            foreach ($row_array as $k =>$v){
                foreach ($v as $v2){
                    $new_row_array[] = $v2;
                }
            }
            // 批量插入分部工程节点
            $success = Db::name('project_divide')->insertAll($new_row_array);
            if(!$success){
                $json_data['status'] = 0;
                $json_data['info'] = '分布工程格式有误';
                return json($json_data);
            }

            /**
             * 批量插入四级节点
             */
            // 获取三级节点的自增编号做为四级节点的pid
            $three_pid = Db::name('project_divide')->whereIn('pid',$second_pid_value)->field('id,sn,primary')->select();
            $three_pid_array = $three_primary_array = [];
            foreach ($three_pid as $pk=>$pv){
                // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                $three_pid_array[$pv['id']] = $pv['sn'];
                if(!empty($pv['primary'])){
                    $three_primary_array[$pv['sn']] = $pv['primary'];
                }
            }
            $row_array = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[4]) && !empty($page_v[5])){ // 前几行都是标题
                        if(in_array($page_v[2],$three_pid_array)){
                            $current_pid =  array_search($page_v[2],$three_pid_array);
                            $current_primary =  array_key_exists($page_v[2],$three_primary_array) ? '是' : '';
                        }
                        $row_array[$i][$pk]['pid'] = $current_pid; // 上级分布工程编号
                        $row_array[$i][$pk]['sn'] = $page_v[4]; // 单元工程编号
                        $row_array[$i][$pk]['name'] = $page_v[5]; // 单元工程名称
                        $row_array[$i][$pk]['primary'] = $current_primary; // 是否主要单元工程 继承上级
                        $row_array[$i][$pk]['job_content'] = $page_v[6]; // 单元工程 工作内容
                        $row_array[$i][$pk]['principle'] = $page_v[7]; // 单元工程 划分原则
                    }
                }
            }
            $new_row_array = [];
            foreach ($row_array as $k =>$v){
                foreach ($v as $v2){
                    $new_row_array[] = $v2;
                }
            }
            // 批量插入单元工程节点
            $success = Db::name('project_divide')->insertAll($new_row_array);
            if(!$success){
                $json_data['status'] = 0;
                $json_data['info'] = '单元工程格式有误';
                return json($json_data);
            }

            $json_data['status'] = 1;
            $json_data['info'] = '导入成功';
            return json($json_data);
        }else{
            $json_data['status'] = 0;
            $json_data['info'] = $file->getError();
            return json($json_data);
        }
    }

    public function test(){
        $second_pid = Db::name('project_divide')->where('pid',1)->field('id,sn,primary')->select();
        $second_pid_value = $second_pid_array = $second_primary_array = [];
        foreach ($second_pid as $pk=>$pv){
            $second_pid_value[] = $pv['id'];
            // 将pid作为下标单，元工程编号sn作为值组成一个一维数组
            $second_pid_array[$pv['id']] = $pv['sn'];
            if(!empty($pv['primary'])){
                $second_primary_array[$pv['sn']] = $pv['primary'];
            }
        }

        $three_pid = Db::name('project_divide')->whereIn('pid',$second_pid_value)->field('id,sn,primary')->select();
        $three_pid_value = $three_pid_array = $three_primary_array = [];
        foreach ($three_pid as $pk=>$pv){
            $three_pid_value[] = $pv['id'];
            // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
            $three_pid_array[$pv['id']] = $pv['sn'];
            if(!empty($pv['primary'])){
                $three_primary_array[$pv['sn']] = $pv['primary'];
            }
        }

        dump($three_pid_value);
        dump($three_pid_array);
    }

    public function importExcelTest(){
        $file = request()->file('excel');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/excel');
        if($info){
            // 调用插件PHPExcel把excel文件导入数据库
            Loader::import('PHPExcel\Classes\PHPExcel', EXTEND_PATH);
            $exclePath = $info->getSaveName();  //获取文件名
            $file_name = ROOT_PATH . 'public' . DS . 'uploads/excel' . DS . $exclePath;   //上传文件的地址
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
            $obj_PHPExcel = $objReader->load($file_name, $encode = 'utf-8');  //加载文件内容,编码utf-8
            $excel_array= $obj_PHPExcel->getsheet(0)->toArray();   // 转换第一页为数组格式
            // 首先导入单位工程，根据单位工程设置根节点和二级节点
            $divide['pid'] = 0;
            $divide['name'] = $excel_array[0][0];
            // 插入前首先判断是否是 重复插入
            $root_pid = Db::name('project_divide_copy')->where('name',$divide['name'])->value('id');
            if(!$root_pid){
                $root_pid = Db::name('project_divide_copy')->insertGetId($divide); // 插入根节点
            }
            // 批量插入二级节点
            $data = $sn_array =[];
            foreach($excel_array as $k=>$v){
                if($k > 0 && !empty($v[3])){
                    $data[$k]['pid'] = $root_pid; // 根节点pid
                    $data[$k]['sn'] = $v[0]; // 单位工程编号
                    $data[$k]['name'] = $v[3]; // 单位工程名称
                    $data[$k]['primary'] = $v[4]; // 是否主要单位工程
                    $sn_array[] = $v[0];
                }
            }
            array_shift($data);  // 删除第一个数组(标题);
            // 如果是同一个文件上传，新上传的将会覆盖之前的。该新增的新增该删除的删除
            $all_data = Db::name('project_divide_copy')->where('pid',$root_pid)->column('id');
            $insert_data = Db::name('project_divide_copy')->whereIn('sn',$sn_array)->column('id');
            $update_data = array_filter($all_data,$insert_data);
            if(1==1){

            }else{
                $success = Db::name('project_divide')->insertAll($data);
            }
            // 获取二级节点的自增编号做为三级节点的pid
            $second_pid = Db::name('project_divide')->where('pid',$root_pid)->field('id,sn,primary')->select();
            $second_pid_value = $second_pid_array = $second_primary_array = [];
            foreach ($second_pid as $pk=>$pv){
                $second_pid_value[] = $pv['id']; // 二级节点的编号
                // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                $second_pid_array[$pv['id']] = $pv['sn'];
                if(!empty($pv['primary'])){
                    $second_primary_array[$pv['sn']] = $pv['primary'];
                }
            }
            /**
             *  批量插入三级节点
             *  这里的页面 一般为 单位工程，分布工程，单元工程三大模块
             **/
            $page_num = $obj_PHPExcel->getSheetCount(); // 获取excel一共有几页
            $row_array = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[2]) && !empty($page_v[3])){ // 前几行都是标题
                        if(in_array($page_v[0],$second_pid_array)){
                            $current_pid =  array_search($page_v[0],$second_pid_array);
                            $current_primary =  array_key_exists($page_v[0],$second_primary_array) ? '是' : '';
                        }
                        $row_array[$i][$pk]['pid'] = $current_pid; // 上级单位工程编号
                        $row_array[$i][$pk]['sn'] = $page_v[2]; // 分部工程编号
                        $row_array[$i][$pk]['name'] = $page_v[3]; // 分部工程名称
                        $row_array[$i][$pk]['primary'] = $current_primary; // 是否主要分部工程 继承上级
                    }
                }
            }
            $new_row_array = [];
            foreach ($row_array as $k =>$v){
                foreach ($v as $v2){
                    $new_row_array[] = $v2;
                }
            }
            // 批量插入分部工程节点
            $success = Db::name('project_divide')->insertAll($new_row_array);
            if(!$success){
                $json_data['status'] = 0;
                $json_data['info'] = '分布工程格式有误';
                return json($json_data);
            }

            /**
             * 批量插入四级节点
             */
            // 获取三级节点的自增编号做为四级节点的pid
            $three_pid = Db::name('project_divide')->whereIn('pid',$second_pid_value)->field('id,sn,primary')->select();
            $three_pid_array = $three_primary_array = [];
            foreach ($three_pid as $pk=>$pv){
                // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                $three_pid_array[$pv['id']] = $pv['sn'];
                if(!empty($pv['primary'])){
                    $three_primary_array[$pv['sn']] = $pv['primary'];
                }
            }
            $row_array = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[4]) && !empty($page_v[5])){ // 前几行都是标题
                        if(in_array($page_v[2],$three_pid_array)){
                            $current_pid =  array_search($page_v[2],$three_pid_array);
                            $current_primary =  array_key_exists($page_v[2],$three_primary_array) ? '是' : '';
                        }
                        $row_array[$i][$pk]['pid'] = $current_pid; // 上级分布工程编号
                        $row_array[$i][$pk]['sn'] = $page_v[4]; // 单元工程编号
                        $row_array[$i][$pk]['name'] = $page_v[5]; // 单元工程名称
                        $row_array[$i][$pk]['primary'] = $current_primary; // 是否主要单元工程 继承上级
                        $row_array[$i][$pk]['job_content'] = $page_v[6]; // 单元工程 工作内容
                        $row_array[$i][$pk]['principle'] = $page_v[7]; // 单元工程 划分原则
                    }
                }
            }
            $new_row_array = [];
            foreach ($row_array as $k =>$v){
                foreach ($v as $v2){
                    $new_row_array[] = $v2;
                }
            }
            // 批量插入单元工程节点
            $success = Db::name('project_divide')->insertAll($new_row_array);
            if(!$success){
                $json_data['status'] = 0;
                $json_data['info'] = '单元工程格式有误';
                return json($json_data);
            }

            $json_data['status'] = 1;
            $json_data['info'] = '导入成功';
            return json($json_data);
        }else{
            $json_data['status'] = 0;
            $json_data['info'] = $file->getError();
            return json($json_data);
        }
    }


}