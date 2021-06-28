<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/23
 * Time: 17:56
 */

namespace app\common\model;


use think\Model;

class Unit extends Model
{
    //Reader
    //根据id获取单位
    public function getUnit($id)
    {
        $res=$this->where('unitID',$id)->find();
        if($res) return $res;
    }
}