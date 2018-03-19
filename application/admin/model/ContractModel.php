<?php

namespace app\admin\model;
use think\Model;
use think\Db;


class ContractModel extends Model
{
    protected $name = 'contract';

    /**
     * 插入信息
     * @param $param
     */
    public function insertContract($param)
    {
        try{
            $result = $this->validate('ContractValidate')->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '合同信息添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * 编辑信息
     * @param $param
     */
    public function editContract($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '合同信息编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    /**
     * [del_article 删除]
     * @return [type] [description]
     */
    public function delContract($id)
    {
        $this->where('id', $id)->delete();
        return ['code' => 1, 'data' => '', 'msg' => '合同信息删除成功'];
    }


    /**
     * [getAllMenu 获取全部合同信息]
     */
    public function getAll()
    {
        return $this->order('id asc')->select();
    }

    /**
     * 根据管理员id获取角色信息
     * @param $id
     */
    public function getOneContract($id)
    {
        return $this->where('id', $id)->find();
    }


    /**
     * 获取标段名称
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBiaoduanName($num)
    {
        $data = $this->field('id,biaoduan_name')->where('biaoduan_name','neq','监理部')->group('biaoduan_name')->select();
        if($num == 1){
            $arr = [1=>['id'=>'1','biaoduan_name'=>'重大危险源识别']];
        }else if($num == 2){
            $arr = [1=>['id'=>'1','biaoduan_name'=>'监理单位'],2=>['id'=>'2','biaoduan_name'=>'施工单位']];
        }else{
            $arr = [1=>['id'=>'1','biaoduan_name'=>'主要负责人和安全管理人员'],2=>['id'=>'2','biaoduan_name'=>'从业人员'],3=>['id'=>'3','biaoduan_name'=>'外来人员']];
        }
        $str = ""; $j = 0;
        for ($i = 1; $i <= $num; $i++){
            $str .= '{ "id": "' . $arr[$i]['id'] . '", "pId":"' . 0 . '", "name":"' . $arr[$i]['biaoduan_name'].'"';
            $str .= '},';
            foreach($data as $key=>$v){
                if($arr[$i]['biaoduan_name'] == '监理单位' && $j == 0){
                    $id = $this->where('biaoduan_name','eq','监理部')->value('id');
                    $id2 = $id + 10; // 避免和pId一样
                    $name = '监理部';
                    $str .= '{ "id": "' . $id2 . '", "pId":"' . $i . '", "name":"' .$name.'"';
                    $str .= '},';
                    $j++;
                }else if($arr[$i]['biaoduan_name'] != '监理单位'){
                    $id3 = $v['id'] + 10; // 避免和pId一样
                    $str .= '{ "id": "' . $id3 . '", "pId":"' . $i . '", "name":"' . $v['biaoduan_name'].'"';
                    $str .= '},';
                }
            }
        }
        return "[" . substr($str, 0, -1) . "]";
    }



}