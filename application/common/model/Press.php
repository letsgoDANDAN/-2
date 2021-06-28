<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 23:11
 */

namespace app\common\model;


use think\Model;

class Press extends Model
{
    //Reader
    //根据id得到出版社
      public function getPressName($id)
      {
          $res=$this->where('publicID',$id)->find();
          if($res)
          {
              return $res;
          }
      }
}