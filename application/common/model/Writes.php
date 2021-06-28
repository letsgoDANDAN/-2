<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 21:35
 */

namespace app\common\model;


use think\Model;

class writes extends Model
{
    //Reader
    //根据isbn查阅作者id
    public function getAuthorID($ISBN)
    {
        $res=$this->where('ISBN',$ISBN)->select();
        if($res)
        {
            return $res;
        }
    }
}