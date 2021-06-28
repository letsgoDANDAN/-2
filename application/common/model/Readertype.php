<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/23
 * Time: 17:46
 */

namespace app\common\model;


use think\Model;

class Readertype extends Model
{
    //Reader
    //通过id获取读者类型
    public function getReaderTypeName($id)
    {
        $res=$this->where('readertypeID',$id)->find();
        if($res)
        {
            return $res;
        }
    }

}