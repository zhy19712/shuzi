<?php
/**
 * Created by PhpStorm.
 * User: sir
 * Date: 2018/3/7
 * Time: 14:01
 */

namespace app\safety\model;


use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\Model;

class RiskModel extends Model
{
    protected $name = 'safety_risk';

    /**
     * 未治理图片
     */
    public function riskImg()
    {
        return $this->hasMany('RiskImgModel', 'risk_id', 'id');
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
                $this->proessScore($risk['founder'], $risk['cat'], '排查', $risk['founddate']);
                $this->proessScore($risk['acceptor'], $risk['cat'], '验收', $risk['completedate']);
                $res = $this->allowField(true)->save($risk);
                $_id = $this->getLastInsID();
            } else {
//            修改，对比发现人与验收人
                $item_old = $this->where('id', $risk['id'])->find();
                if (!$item_old['founder'] == $risk['founder']) {
                    $this->proessScore($item_old['founder'], $item_old['cat'], '修改', $item_old['founddate'], true);
                    $this->proessScore($risk['founder'], $risk['cat'], '排查', $risk['founddate']);
                }
                if (!$item_old['acceptor'] == $risk['acceptor']) {
                    $this->proessScore($item_old['acceptor'], $item_old['cat'], '修改', $risk['acceptor_id'], true);
                    $this->proessScore($risk['acceptor'], $risk['cat'], '验收', $risk['acceptor_id']);
                }
                $res = $this->allowField(true)->save($risk, ['id' => $risk['id']]);
                $_id = $risk['id'];
            }
            try {
                $item = $this->where('id', $_id)->find();
                $riskImgs = array();
                if (array_key_exists('risk_img', $risk)) {
                    foreach (explode('※', $risk['risk_img']) as $img) {
                        $riskImgs[] = array('path' => $img, 'cat' => '排查');
                    }
                }
                if (array_key_exists('risk_after_img', $risk)) {
                    foreach (explode('※', $risk['risk_after_img']) as $img) {
                        $riskImgs[] = array('path' => $img, 'cat' => '验收');
                    }
                }
                RiskImgModel::where('risk_id', $item['id'])->delete();
                $item->riskImg()->saveAll($riskImgs);
            }catch (Exception $e)
            {
                return ['code' => -1, 'data' => '', 'msg' =>$e->getMessage()];
            }
            return ['code' => 1, 'data' => '', 'msg' => '操作成功'];
        } catch (PDOException $e) {
            return ['code' => -2, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    /**
     * 分数计算
     * @param $userId
     * @param $cat
     * @param $act
     * @param $time
     * @param bool $isEdit 是否编辑修改
     * @return bool
     */
    function proessScore($user, $cat, $act, $time, $isEdit = false)
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
            if ($isEdit) {
                $score = 0 - $score;
            }
            $duty = new RiskDoubleDutyModel();
            return $duty->prossScore($user, $score, $cat, $act, $time);
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
        $mod = $data = RiskModel::with('RiskImg')->where('id', $id)->find();   //select([$id]);
        if (count(($mod['risk_img'])) > 0) {
            $risk_img_after = array();
            $risk_img_before = array();
            foreach ($mod['risk_img'] as $item) {
                if ($item['cat'] == '排查') {
                    $risk_img_before[] = $item;
                } else {
                    $risk_img_after[] = $item;
                }
            }
            $mod['risk_img_before'] = $risk_img_before;
            $mod['risk_img_after'] = $risk_img_after;
        }
        return $mod;
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