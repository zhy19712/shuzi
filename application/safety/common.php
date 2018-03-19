<?php

/**
 * 清除数组中的空数组
 * @param $ar
 * @return mixed
 * @author hutao
 */
function delArrayNull($ar){
    foreach($ar as $k=>$v){
        $v = array_filter($v);
        if(empty($v) || is_null($v)){
            unset($ar[$k]);
        }
    }
    return $ar;
}
