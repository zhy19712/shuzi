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
use app\admin\validate\ProjectDivideValidate;
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
        $project = new ProjectModel();

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
     * author hutao
     */
    public function importExcel(){
        $file = request()->file('file');
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
            $first_data['pid'] = 0;
            $first_data['name'] = $excel_array[0][0];
            // 插入前首先判断是否是 重复插入
            $root_pid = Db::name('project_divide')->where('name',$first_data['name'])->value('id');
            if(!$root_pid){
                // 1,插入根节点
                $root_pid = Db::name('project_divide')->insertGetId($first_data); // 插入根节点
            }
            /**
             * 2,批量插入二级节点
             * 如果是同一个文件上传，新上传的将会覆盖之前的。该新增的新增 -- 该删除的删除
             * $second_sn_array 是上传的单位工程编号的总记录  P1-11 P1-12 P1-13  P1-14 P1-15
             * $second_update_array 是数据库里单位工程编号的总记录 null 或者 P1-11 P1-12 P1-13个数不一定和$sn_data一样或多或少
             * $second_insert_array 是$second_sn_array,$second_update_array的差集 null 或者 P1-11 P1-12 P1-13个数不一定和$sn_data一样或多或少
             */
            $second_update_array = Db::name('project_divide')->where('pid',$root_pid)->column('id,sn');
            $second_data = $second_sn_array = [];$i=0;
            foreach($excel_array as $k=>$v){
                // 这里可以做格式的验证 就判断$v[0]是否是字母数字下划线的组合 例如 P1-11
                // 这里可以做格式的验证 就判断$v[0]是否是字母数字下划线的组合 例如 P1-11
                // 这里可以做格式的验证 就判断$v[0]是否是字母数字下划线的组合 例如 P1-11
                if($k > 1 && !empty($v[3])){
                    $second_data[$i]['pid'] = $root_pid; // 根节点pid
                    $second_data[$i]['sn'] = $v[0]; // 单位工程编号
                    $second_data[$i]['name'] = $v[3]; // 单位工程名称
                    $second_data[$i]['primary'] = $v[4]; // 是否主要单位工程
                    $second_sn_array[] = $v[0]; // 上传的单位工程编号的总记录
                    // 此次上传的内容是否已经存在，存在就进行覆盖
                    $second_id_num = array_search($v[0],$second_update_array); // 找到并返回键名做为更新的条件id编号
                    if($second_id_num){
                        $second_data[$i]['id'] = $second_id_num;
                        $success = Db::name('project_divide')->update($second_data[$i]);
                    }
                    $i++;
                }
            }
            $second_insert_array = array_diff($second_sn_array,$second_update_array); // 差集,除去更新的剩下的就是要新增的
            $second_insert_data = [];
            foreach ($second_insert_array as $k=>$v){
                $second_insert_data[$k] = $second_data[$k];
            }
            $success = Db::name('project_divide')->insertAll($second_insert_data);
            /**
             *  3,批量插入三级节点
             *  这里的页面 一般为 单位工程，分布工程，单元工程三大模块
             **/
            // 获取二级节点的自增编号做为三级节点的pid
            $second_list = Db::name('project_divide')->where('pid',$root_pid)->field('id,sn,primary')->select();
            $second_id_value = $second_id_array = $second_primary_array = [];
            foreach ($second_list as $pk=>$pv){
                $second_id_value[] = $pv['id']; // 二级节点的编号
                $second_id_array[$pv['id']] = $pv['sn']; // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                if(!empty($pv['primary'])){
                    $second_primary_array[$pv['sn']] = $pv['primary']; // 是否主要分部工程 继承上级
                }
            }
            // 数据库里所有的三级节点编号
            $three_update_array = Db::name('project_divide')->whereIn('pid',$second_id_value)->column('id,sn');
            $page_num = $obj_PHPExcel->getSheetCount(); // 获取excel一共有几页
            $three_data = $three_sn_array =[];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[2]) && !empty($page_v[3])){ // 前几行都是标题
                        // 这里可以做格式的验证 就判断$page_v[2]是否是字母数字下划线的组合 例如 P1-11
                        // 这里可以做格式的验证 就判断$page_v[2]是否是字母数字下划线的组合 例如 P1-11
                        // 这里可以做格式的验证 就判断$page_v[2]是否是字母数字下划线的组合 例如 P1-11
                        $three_sn_array[] = $page_v[2]; // 上传的分部工程编号的总记录
                        // 这里的in_array验证不能去掉，其他页面的首列是上级编号，
                        // 上级编号只写了一次，子类要继承pid这里就要做到当首列一直为空时，保留第一次获取的pid
                        // 例如: P1-11	上库主坝	P1-11-1	 名称xxx
                        //                          P1-11-2	 名称xxx
                        //                          P1-11-3	 名称xxx
                        //                          P1-11-4	 名称xxx
                        //      P1-12	下库主坝	P1-12-1	 名称xxx
                        //                          P1-12-2	 名称xxx
                        //                          P1-12-3	 名称xxx
                        //                          P1-12-4	 名称xxx
                        if(in_array($page_v[0],$second_id_array)){
                            $current_pid =  array_search($page_v[0],$second_id_array);// 找到并返回键名做为上级pid
                            $current_primary =  array_key_exists($page_v[0],$second_primary_array) ? '是' : '';
                        }
                        $three_data[$i][$pk]['pid'] = $current_pid;
                        $three_data[$i][$pk]['sn'] = $page_v[2]; // 分部工程编号
                        $three_data[$i][$pk]['name'] = $page_v[3]; // 分部工程名称
                        $three_data[$i][$pk]['primary'] = $current_primary; // 是否主要分部工程 继承上级
                        // 此次上传的内容是否已经存在，存在就进行覆盖
                        $three_id_num = array_search($page_v[2],$three_update_array); // 找到并返回键名做为更新的条件id编号
                        if($three_id_num){
                            $three_data[$i][$pk]['id'] = $three_id_num;
                            $success = Db::name('project_divide')->update($three_data[$i][$pk]);
                        }
                    }
                }
            }
            $three_new_data = [];
            foreach ($three_data as $k =>$v){
                foreach ($v as $v2){
                    $three_new_data[] = $v2; // 将三维数组改为二维数组
                }
            }
            // 批量插入分部工程节点
            $three_insert_array = array_diff($three_sn_array,$three_update_array); // 差集,除去更新的剩下的就是要新增的
            $three_insert_data = [];
            foreach ($three_insert_array as $k=>$v){
                $three_insert_data[$k] = $three_new_data[$k];
            }
            $success = Db::name('project_divide')->insertAll($three_insert_data);
            /**
             * 4,批量插入四级节点
             */
            // 获取三级节点的自增编号做为四级节点的pid
            $three_list = Db::name('project_divide')->whereIn('pid',$second_id_value)->field('id,sn,primary')->select();
            $three_id_value = $three_id_array = $three_primary_array = [];
            foreach ($three_list as $pk=>$pv){
                $three_id_value[] = $pv['id'];
                // 将pid作为下标，单元工程编号sn作为值组成一个一维数组
                $three_id_array[$pv['id']] = $pv['sn'];
                if(!empty($pv['primary'])){
                    $three_primary_array[$pv['sn']] = $pv['primary'];
                }
            }
            // 数据库里所有的四级节点编号
            $four_update_array = Db::name('project_divide')->whereIn('pid',$three_id_value)->column('id,sn');
            $four_data = $four_sn_array = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[4]) && !empty($page_v[5])){ // 前几行都是标题
                        $four_sn_array[] = $page_v[4];
                        if(in_array($page_v[2],$three_id_array)){
                            $current_pid =  array_search($page_v[2],$three_id_array);
                            $current_primary =  array_key_exists($page_v[2],$three_primary_array) ? '是' : '';
                        }
                        $four_data[$i][$pk]['pid'] = $current_pid; // 上级分布工程编号
                        $four_data[$i][$pk]['sn'] = $page_v[4]; // 单元工程编号
                        $four_data[$i][$pk]['name'] = $page_v[5]; // 单元工程名称
                        $four_data[$i][$pk]['primary'] = $current_primary; // 是否主要单元工程 继承上级
                        $four_data[$i][$pk]['job_content'] = $page_v[6]; // 单元工程 工作内容
                        $four_data[$i][$pk]['principle'] = $page_v[7]; // 单元工程 划分原则
                        // 此次上传的内容是否已经存在，存在就进行覆盖
                        $four_id_num = array_search($page_v[4],$four_update_array); // 找到并返回键名做为更新的条件id编号
                        if($four_id_num){
                            $four_data[$i][$pk]['id'] = $four_id_num;
                            $success = Db::name('project_divide')->update($four_data[$i][$pk]);
                        }
                    }
                }
            }
            $four_new_data = [];
            foreach ($four_data as $k =>$v){
                foreach ($v as $v2){
                    $four_new_data[] = $v2;
                }
            }
            // 批量插入单元工程节点
            $four_insert_array = array_diff($four_sn_array,$four_update_array); // 差集,除去更新的剩下的就是要新增的
            $four_insert_data = [];
            foreach ($four_insert_array as $k=>$v){
                $four_insert_data[$k] = $four_new_data[$k];
            }
            $success = Db::name('project_divide')->insertAll($four_insert_data);
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