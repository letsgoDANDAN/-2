<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/25
 * Time: 0:06
 */

namespace app\common\model;


use think\Model;

class Book extends Model
{
    //Reader
    //根据id寻找图书
    public function getBook($id)
    {
        $res=$this->where('bookID',$id)->find();
        return $res;
    }

    //更新图书信息
    public function updateBook($data)
    {
        if($this->where('bookID',$data['bookID'])
            ->update([
               'readerstatus'=>$data['readerstatus'] ,
                'details'=>$data['details'],
            ])){
        return 1;}else{
            return 0;
        }
    }
    //根据ISBN寻找实体书
    public function getBooks($isbn)
    {
        $res=$this->alias('b')
            ->join('Library l','l.branchID=b.branchID','left')
            ->where('ISBN',$isbn)
            ->select();
        return $res;
    }



    //Admin
    //新增实体书
    public function addbook($data)
    {
        //检查
        $validate = new \app\common\validate\Book();
        if($validate->scene('addbook')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where('bookID',$data['bookID'])->find();
        if($res)
        {
            return "添加失败，该书本ID已存在！";
        }
        $this->create($data);
        return 1;
    }

    //更新图书
    public function editbook($data)
    {
        //检查
        $validate = new \app\common\validate\Book();
        if($validate->scene('editbook')->check($data)==0)
        {
            return $validate->getError();
        }

        //数据库
        $res=$this->where('bookID',$data['bookID'])->find();
        if($res)
        {
            $res['branchID']=$data['branchID'];
            $res['readerstatus']=$data['readerstatus'];
            $res['details']=$data['details'];
            $res->save();
            return 1;
        }
    }
}