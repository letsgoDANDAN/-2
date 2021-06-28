<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 21:40
 */

namespace app\common\model;


use think\Model;

class Author extends Model
{
    //Reader
    //根据id得到作者名字
     public function getAuthorName($id)
     {
         $res=$this->where('authorID',$id)->find();
         if($res)
         {
             return $res;
         }
     }
}