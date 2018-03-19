<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/7
 * Time: 14:01
 */

namespace app\safety\model;


use think\exception\PDOException;
use think\Model;

class RiskModel extends Model
{
    protected $name = 'safety_risk';

    /**
     * 发现人
     */
    public function fouder()
    {
        return $this->hasOne('User', 'founder_id')->field('nickname');
    }

    /**
     * 责任人
     */
    public function workduty()
    {
        return $this->hasOne('User', 'workduty_id')->field('nickname');
    }

    /**
     * 验收人
     */
    public function acceptor()
    {
        return $this->hasOne('User', 'acceptor_id')->field('nickname');
    }
//    /**
//     * 标段
//     */
//    public function section('User','section_id');

    public function insertOrEdit($risk)
    {
        try {
            if (empty($risk['id'])) {
//            新增，直接计算分数
                $founder_id = $risk['founder_id'];
                $this->proessScore($founder_id, $risk['cat'], '排查', $risk['founddate']);
                $acceptor_id = $risk['acceptor_id'];
                $this->proessScore($acceptor_id, $risk['cat'], '验收', $risk['completedate']);
                $res = $this->allowField(true)->save($risk);
                if ($res) {
                    return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
                } else {
                    return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
                }
            } else {
//            修改，对比发现人与验收人
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


    function proessScore($userId, $cat, $act, $time)
    {
        $score = 0;
        switch ($cat) {
            case '脚手架':
            case '环水保':
            case '施工用电':
            case '施工机具':
            case '起重作业':
            case '交通安全':
                $score = 0.5;
                break;
            case '安全文明施工':
            case '反违章':
            case '警示标示':
            case '消防安全':
            case '职业健康':
                $score = 0.2;
                break;
            case '特种设备':
                $score = 2;
                break;
            case '地质灾害':
            case '爆破作业':
                $score = 5;
                break;
            case '重大事故隐患':
                $score = 10;
                break;
            default:
                $score = 0;
        }
        if (!$score == 0) {
            $duty = new RiskDoubleDutyModel();
            $duty = $duty->prossScore($userId,$score,$cat,$act,$time);
        }
    }

    public function insertEdu($param)
    {
        try {
            $result = $this->allowField(true)->save($param);
            if (false === $result) {
                return ['code' => -1, 'data' => '', 'msg' => $this->getError()];
            } else {
                return ['code' => 1, 'data' => '', 'msg' => '添加成功'];
            }
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function editEdu($param)
    {
        try {
            $result = $this->allowField(true)->save($param, ['id' => $param['id']]);
            if (false === $result) {
                return ['code' => 0, 'data' => '', 'msg' => $this->getError()];
            } else {
                return ['code' => 1, 'data' => '', 'msg' => '编辑成功'];
            }
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delEdu($id)
    {
        try {
            $data = $this->getOne($id);
            $path = $data['ma_path'];
            $pdf_path = './uploads/temp/' . basename($path) . '.pdf';
            if (file_exists($path)) {
                unlink($path); //删除文件 培训材料文件
            }
            if (file_exists($pdf_path)) {
                unlink($pdf_path); //删除生成的预览pdf
            }
            $path2 = $data['re_path'];
            $pdf_path2 = './uploads/temp/' . basename($path2) . '.pdf';
            if (file_exists($path2)) {
                unlink($path2); //删除文件 培训记录文件
            }
            if (file_exists($pdf_path2)) {
                unlink($pdf_path2); //删除生成的预览pdf
            }
            $import_path = $data['path'];
            if (file_exists($import_path)) {
                unlink($path); //删除文件 导入的文件
            }

            $this->where('id', $id)->delete();
            return ['code' => 1, 'data' => '', 'msg' => '删除成功'];
        } catch (PDOException $e) {
            return ['code' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function getOne($id)
    {
        return $this->where('id', $id)->find();
    }

    public function getList($idArr)
    {
        $data = [];
        foreach ($idArr as $v) {
            $data[] = $this->getOne($v);
        }
        return $data;
    }

    public function getYears()
    {
        return $this->where('improt_time is not null')->group('improt_time')->column('improt_time');
    }
}