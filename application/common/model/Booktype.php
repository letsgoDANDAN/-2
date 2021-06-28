<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 21:27
 */

namespace app\common\model;


use think\Model;

class Booktype extends Model
{
    //Reader
    //根据sonid得到图书分类
     public function getBysonID($sonID)
     {
         $res=$this->where('sonID',$sonID)->find();
         if($res)
         {
             return $res;
         }

     }
     //得到所有父类
    public function getFather()
    {
        $res=$this->whereNull('fatherID')->select();
            return $res;
    }
    //得到子类
    public function getSon($id)
    {
        $res=$this->where('fatherID',$id)->select();
        return $res;
    }
}