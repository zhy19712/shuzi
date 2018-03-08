<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/3/7
 * Time: 13:57
 */
//机构和职责三级分类上传文件增删改查
namespace app\safety\model;


use think\exception\PDOException;
use think\Model;
class ResponsibilityinstyGroupModel extends Model
{
    protected $name = 'safety_responsibilityinsty_group';

     /*
     * 查询预览单条记录
     */
    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

     /*
     * 添加新的文件，文件名、上传人等一些信息
     */
    public function insertResponsibilityinstyGroup($param)
    {
        try{
            $result = $this->allowField(true)->save($param);
            if(false === $result){
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        }catch( PDOException $e){
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /*
    *对上传的文件进行编辑
    */

    public function editResponsibilityinstyGroup($param)
    {
        try{
            $result =  $this->allowField(true)->save($param, ['id' => $param['id']]);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            }else{
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

     /*
     *对上传的文件进行删除
     */

    public function delResponsibilityinstyGroup($id)
    {
        try{
            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];

        }catch( PDOException $e){
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }
}