<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/22
 * Time: 15:18
 */

namespace app\common\model;
use think\Db;
use think\Model;

class Bookdescribe extends Model
{
    //Reader
    //根据ISBN得到图书描述
     public function findByISBN($ISBN)
     {
         $res=$this->where('ISBN',$ISBN)->find();
         if($res) {
              return $res;
         }
     }
     //模糊查询
    public function searchBook($key)
    {
        $res=$this->where('ISBN|bookname|sonID|introduce','like','%'.$key.'%')->paginate(5);
        return $res;
    }



    //Admin
    //增加图书描述
    public function addbookdescribe($data)
    {
        //检查
        $validate = new \app\common\validate\Bookdescribe();
        if($validate->scene('addbookdescribe')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where('ISBN',$data['ISBN'])->find();
        if($res)
        {
            return "添加失败，该ISBN已存在！";
        }
        $this->create($data);
        return 1;
    }

    //更新图书描述
    public function editbookdescribe($data)
    {
        //检查
        $validate = new \app\common\validate\Bookdescribe();
        if($validate->scene('editbookdescribe')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where('ISBN',$data['ISBN'])->find();
        if($res)
        {
            $res['sonID']=$data['sonID'];
            $res['publicID']=$data['publicID'];
            $res['borrowtypeID']=$data['borrowtypeID'];
            $res['bookname']=$data['bookname'];
            $res['price']=$data['price'];
            $res['introduce']=$data['introduce'];
            $res['picture']=$data['picture'];
            $res->save();
            return 1;
        }
    }
}