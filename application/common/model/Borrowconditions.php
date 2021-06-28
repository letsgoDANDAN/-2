<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 23:34
 */

namespace app\common\model;


use think\Model;

class Borrowconditions extends Model
{
    //Reader
    //根据读者类型id和图书借阅类型id得到借阅条件
    public function selectByType($data)
    {
        $res=$this->where($data)->find();
        if($res)
        {
            return $res;
        }
    }

}