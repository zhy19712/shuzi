<?php
/**
 * Created by PhpStorm.
 * User: waterforest
 * Date: 2017/12/13
 * Time: 8:53
 */

namespace app\admin\controller;
use app\admin\model\DivideModel;
use app\admin\model\HunningtuModel;
use app\admin\model\KaiwaModel;
use app\admin\model\ProjectModel;
use app\admin\model\ZhihuModel;
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
        $kaiwa = new KaiwaModel();
        $zhihu = new ZhihuModel();
        $hunningtu = new HunningtuModel();
        $param = input('post.');
        if(request()->isAjax()){

            if(empty($param['id']))
            {

                $flag = $project->insertProject($param);
                $data = [
                    'uid' => $project->getLastInsID()
                ];
                if($param['cate'] == '开挖'){
                    $kaiwa->insertKaiwa($data);
                }else if( $param['cate'] == '支护'){
                    $zhihu->insertZhihu($data);
                }else if( $param['cate'] == '混凝土'){
                    $hunningtu->insertHunningtu($data);
                }
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
     * 导入excel文件
     * @return \think\response\Json
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     * @author huao
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
            // 验证格式 ---- 去除顶部菜单名称中的空格，并根据名称所在的位置确定对应列存储什么值
            $sn_index = $name_index = $primary_index = -1;
            foreach ($excel_array[1] as $k=>$v){
                $str = preg_replace('/[ ]/', '', $v);
                if($str == '编号'){
                    $sn_index = $k;
                }else if ($str == '单位工程名称'){
                    $name_index = $k;
                }else if ($str == '是否主控项目'){
                    $primary_index = $k;
                }
            }
            if($sn_index == -1 || $name_index == -1 || $primary_index == -1){
                $json_data['status'] = 0;
                $json_data['info'] = '首页的格式不对 - 01 (缺少[编号][单位工程名称][是否主控项目])';
                return json($json_data);
            }
            // 插入前首先判断是否是 重复插入
            $root_pid = Db::name('project_divide')->where('name',$first_data['name'])->value('id');
            if(!$root_pid){
                // 1,插入根节点
                $root_pid = Db::name('project_divide')->insertGetId($first_data); // 插入根节点
            }else{
                // 获取二级子类 三级子类 四级子类
                $second_subclass = $three_subclass = $four_subclass = [];
                $second_subclass = Db::name('project_divide')->where('pid',$root_pid)->column('id');
                if(!empty($second_subclass)){ // 为空验证避免误删一级节点
                    $three_subclass = Db::name('project_divide')->whereIn('pid',$second_subclass)->column('id');
                    if(!empty($three_subclass)){
                        $four_subclass = Db::name('project_divide')->whereIn('pid',$three_subclass)->column('id');
                    }
                }
                // 删除所有子类
                $success = Db::name('project_divide')->delete($four_subclass);
                $success = Db::name('project_divide')->delete($three_subclass);
                $success = Db::name('project_divide')->delete($second_subclass);
            }
            /**
             * 2,批量插入二级节点
             * 如果是同一个文件上传，新上传的将会覆盖之前的。
             */
            $second_data = [];
            foreach($excel_array as $k=>$v){
                if($k > 1 && !empty($v[$sn_index]) && !empty($v[$name_index])){
                    $second_data[$k]['pid'] = $root_pid; // 根节点pid
                    $second_data[$k]['sn'] = $v[$sn_index]; // 单位工程编号
                    $second_data[$k]['name'] = $v[$name_index]; // 单位工程名称
                    $second_data[$k]['primary'] = $v[$primary_index]; // 是否主要单位工程
                }
            }
            $success = Db::name('project_divide')->insertAll($second_data);
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
            $page_num = $obj_PHPExcel->getSheetCount(); // 获取excel一共有几页
            $three_data =[];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[2]) && !empty($page_v[3])){ // 前几行都是标题
                        // 这里的in_array验证不能去掉，其目的是用来保留首次获取的上级编号
                        // 上级编号只写了一次，子类要继承pid这里就要做到当第一个单元格为空时，就继承第一次获取的pid
                        // 例如: P1-11	上库主坝	P1-11-1	 名称xxx
                        //                          P1-11-2	 名称xxx
                        //                          P1-11-3	 名称xxx
                        //      P1-12	下库主坝	P1-12-1	 名称xxx
                        //                          P1-12-2	 名称xxx
                        //                          P1-12-3	 名称xxx
                        if(in_array($page_v[0],$second_id_array)){
                            $current_pid =  array_search($page_v[0],$second_id_array);// 找到并返回键名做为上级pid
                            $current_primary =  array_key_exists($page_v[0],$second_primary_array) ? '是' : '';
                        }
                        // 如果pid不存在说明这里的第一个单元格的值为空或者不正确
                        if(!empty($page_v[0]) && !in_array($page_v[0],$second_id_array)){
                            $json_data['status'] = 0;
                            $json_data['info'] = '编号有误 -02 不存在的上级编号'.$page_v[0].'请检查第'.($i+1).'页第'.($pk+1).'行的首个单元格的编号';
                            return json($json_data);
                        }
                        $three_data[$i][$pk]['pid'] = $current_pid;
                        $three_data[$i][$pk]['sn'] = $page_v[2]; // 分部工程编号
                        $three_data[$i][$pk]['name'] = $page_v[3]; // 分部工程名称
                        $three_data[$i][$pk]['primary'] = $current_primary; // 是否主要分部工程 继承上级
                    }
                }
            }
            $three_new_data = [];
            foreach ($three_data as $k =>$v){
                foreach ($v as $v2){
                    if(!empty($v2['pid'])){
                        $three_new_data[] = $v2; // 将三维数组改为二维数组
                    }
                }
            }
            // 批量插入分部工程节点
            $success = Db::name('project_divide')->insertAll($three_new_data);
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
            $four_data = [];
            for ($i=1;$i<$page_num;$i++){ // $i=1 第一页已经导入成功，这里从第二页开始导入
                $currentSheet = $obj_PHPExcel->getsheet($i)->toArray();   // 将每一页的数据转换为数组格式
                $current_pid = $current_primary = null;
                foreach ($currentSheet as $pk=>$page_v){ // 循环每一页的数组
                    if($pk > 2 && !empty($page_v[4]) && !empty($page_v[5])){ // 前几行都是标题
                        if(in_array($page_v[2],$three_id_array)){
                            $current_pid =  array_search($page_v[2],$three_id_array);
                            $current_primary =  array_key_exists($page_v[2],$three_primary_array) ? '是' : '';
                        }
                        if(!empty($page_v[2]) && !in_array($page_v[2],$three_id_array)){
                            $json_data['status'] = 0;
                            $json_data['info'] = '编号有误 -03 不存在的上级编号'.$page_v[2].'请检查第'.($i+1).'页第'.($pk+1).'行的首个单元格的编号';
                            return json($json_data);
                        }
                        $four_data[$i][$pk]['pid'] = $current_pid; // 上级分布工程编号
                        $four_data[$i][$pk]['sn'] = $page_v[4]; // 单元工程编号
                        $four_data[$i][$pk]['name'] = $page_v[5]; // 单元工程名称
                        $four_data[$i][$pk]['primary'] = $current_primary; // 是否主要单元工程 继承上级
                        $four_data[$i][$pk]['job_content'] = $page_v[6]; // 单元工程 工作内容
                        $four_data[$i][$pk]['principle'] = $page_v[7]; // 单元工程 划分原则
                    }
                }
            }
            $four_new_data = [];
            foreach ($four_data as $k =>$v){
                foreach ($v as $v2){
                    if(!empty($v2['pid'])){
                        $four_new_data[] = $v2;
                    }
                }
            }
            // 批量插入单元工程节点
            $success = Db::name('project_divide')->insertAll($four_new_data);
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