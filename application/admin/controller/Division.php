<?php
/**
 * Created by PhpStorm.
 * User: zhifang
 * Date: 2018/3/27
 * Time: 16:16
 */

namespace app\admin\controller;
use app\admin\model\PictureModel;
use app\admin\model\PictureRelationModel;
use think\Db;
use think\Loader;
/**
 * 工程划分
 * Class Division
 * @package app\quality\controller
 */
class Division extends Base{
    /**
     * 获取 左侧工程划分下 所有的模型图编号
     * 点击 工程划分 获取该 节点 包含的所有单元工程段号(单元划分) 对应的模型图编号 一对多
     * 这里展示的是 完整的模型图
     * @return \think\response\Json
     * @author hutao
     */
    public function modelPictureAllNumber()
    {
        // 前台 传递 选中的 工程划分 编号 add_id
        if($this->request->isAjax()){
            $param = input('param.');
            $add_id = isset($param['add_id']) ? $param['add_id'] : -1;
            if($add_id == -1){
                return json(['code' => 0,'msg' => '编号有误']);
            }
            $id = Db::name('project')->where('pid',$add_id)->column('id');
            // 获取关联的模型图
            $picture = new PictureRelationModel();
            $data= $picture->getAllNumber($id);
            $picture_number = $data['picture_number_arr'];
            return json(['code'=>1,'numberArr'=>$picture_number,'msg'=>'工程划分-模型图编号']);
        }
    }

    /**
     * 点击 单元工程段号(单元划分) 展示关联的模型图 一对一关联
     * 这里展示的是部分模型图
     * @return \think\response\Json
     * @author hutao
     */
    public function modelPicturePreview()
    {
        // 前台 传递 选中的 单元工程段号 编号 id
        if($this->request->isAjax()){
            $param = input('param.');
            $id = isset($param['id']) ? $param['id'] : -1;
            if($id == -1){
                return json(['code' => 0,'msg' => '编号有误']);
            }
            // 获取关联的模型图
            $picture = new PictureRelationModel();
            $data = $picture->getAllNumber([$id]);
            $picture_number = $data['picture_number_arr'];
            return json(['code'=>1,'number'=>$picture_number,'msg'=>'单元工程段号-模型图编号']);
        }
    }

    /**
     * 打开关联模型 页面 openModelPicture
     * @return mixed|\think\response\Json
     * @author hutao
     */
    public function openModelPicture()
    {
        // 前台 传递 选中的 单元工程段号的 id编号
        if($this->request->isAjax()){
            $param = input('param.');
            $id = isset($param['id']) ? $param['id'] : -1;
            if($id == -1){
                return json(['code' => 0,'msg' => '编号有误']);
            }
            // 获取工程划分下的 所有的模型图主键,编号,名称
            $picture = new PictureModel();
            $data = $picture->getAllName($id);
            return json(['code'=>1,'one_picture_id'=>$data['one_picture_id'],'data'=>$data['str'],'msg'=>'模型图列表']);
        }
        return $this->fetch('relationview');
    }

    /**
     * 关联模型图
     * @return \think\response\Json
     * @author hutao
     */
    public function addModelPicture()
    {
        // 前台 传递 单元工程段号(单元划分) 编号id  和  模型图主键编号 picture_id
        if($this->request->isAjax()){
            $param = input('param.');
            $relevance_id = isset($param['id']) ? $param['id'] : -1;
            $picture_id = isset($param['picture_id']) ? $param['picture_id'] : -1;
            if($relevance_id == -1 || $picture_id == -1){
                return json(['code' => 0,'msg' => '参数有误']);
            }
            // 是否已经关联过 picture_type  1工程划分模型 2 建筑模型 3三D模型
            $is_related = Db::name('quality_model_picture_relation')->where(['type'=>1,'relevance_id'=>$relevance_id])->value('id');
            $data['type'] = 1;
            $data['relevance_id'] = $relevance_id;
            $data['picture_id'] = $picture_id;
            $picture = new PictureRelationModel();
            if(empty($is_related)){
                // 关联模型图 一对一关联
                $flag = $picture->insertTb($data);
                return json($flag);
            }else{
                $data['id'] = $is_related;
                $flag = $picture->editTb($data);
                return json($flag);
            }
        }
    }

    /**
     * 搜索模型
     * @return \think\response\Json
     * @author hutao
     */
    public function searchModel()
    {
        // 前台 传递  选中的 单元工程段号的 id编号  和  搜索框里的值 search_name
        if($this->request->isAjax()){
            $param = input('param.');
            $id = isset($param['id']) ? $param['id'] : -1;
            $search_name = isset($param['search_name']) ? $param['search_name'] : -1;
            if($id == -1){
                return json(['code' => 0,'msg' => '请传递选中的单元工程段号的编号']);
            }if($id == -1 || $search_name == -1){
                return json(['code' => 0,'msg' => '请写入需要搜索的值']);
            }
            // 获取搜索的模型图主键,编号,名称
            $picture = new PictureModel();
            $data = $picture->getAllName($id,$search_name);
            return json(['code'=>1,'one_picture_id'=>$data['one_picture_id'],'data'=>$data['str'],'msg'=>'模型图列表']);
        }
    }
}