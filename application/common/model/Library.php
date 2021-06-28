<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/25
 * Time: 13:05
 */

namespace app\common\model;


use think\Model;

class Library extends Model
{
    //Reader
    //根据id得到图书馆
    public function getByID($id)
    {
        $res=$this->where('branchID',$id)->find();
        return $res;
    }
}