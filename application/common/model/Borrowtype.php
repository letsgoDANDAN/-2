<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 23:18
 */

namespace app\common\model;


use think\Model;

class Borrowtype extends Model
{
    //Reader
    //根据id得到借阅类型
    public function getBorrowTypeName($id)
    {
        $res=$this->where('borrowtypeID',$id)->find();
        if($res)
        {
            return $res;
        }

    }
}