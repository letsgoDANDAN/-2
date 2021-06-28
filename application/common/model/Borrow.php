<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/23
 * Time: 23:37
 */

namespace app\common\model;


use think\Model;

class Borrow extends Model
{
    //Reader
    //通过用户id寻找该用户尚未归还和已归还的借阅
    public function getBorrowByID($data)
    {
        $res=$this->alias('b1')
            -> join('book b2','b1.bookID=b2.bookID','left')
            ->join('bookdescribe b3','b2.ISBN=b3.ISBN','left')
            ->join('library l','b2.branchID=l.branchID','left')
            -> where($data)->select();
        return $res;
    }
    //通过用户id寻找该用户正在逾期的借阅
    public function getBorrowByIDOverTime($data)
    {
        $data['returnstatus']='0';
        $res=$this->alias('b1')
            -> join('book b2','b1.bookID=b2.bookID','left')
            ->join('bookdescribe b3','b2.ISBN=b3.ISBN','left')
            ->join('library l','b2.branchID=l.branchID','left')
            -> where($data)
            -> whereTime('returntime','<',date('Y-m-d'))
            ->select();
        return $res;
    }
    //添加借阅信息
    public function insertBorrow($data)
    {
        if(
            $this->insert($data)
        ){
            return 1;
        }else return 0;
    }
    //寻找某一指定的借阅信息
    public function findOne($data)
    {
        $res=$this->where('userid',$data['userid'])
            ->where('bookID',$data['bookID'])
            ->where('returnstatus',$data['returnstatus'])
            ->find();
        return $res;
    }
    //续期，更新日期
    public function updateReturntime($data)
    {
        $this->where('userid',$data['userid'])
            ->where('bookID',$data['bookID'])
            ->where('returnstatus',$data['returnstatus'])
            ->update(['returntime'=>$data['returntime'],'renewstatus'=>'0']);
    }
    //完成借阅，更新数据
    public function finish($data)
    {
        $this->where('userid',$data['userid'])
            ->where('bookID',$data['bookID'])
            ->where('returnstatus','0')
            ->update(['returnstatus'=>'1','time'=>$data['time']]);
    }
    //寻找指定年份的报表
    public function findByTime($data)
    {
        $res=$this->where('userid',$data['userid'])
            ->where('borrowingtime','>',$data['sT'])
            ->where('borrowingtime','<',$data['eT'])
            ->select();
        return $res;
    }






    //Admin
    //修改借阅记录
    public function editborrow($data)
    {
        //检查
        $validate = new \app\common\validate\Borrow();
        if ($validate->scene('editborrow')->check($data) == 0) {
            return $validate->getError();
        }

        //数据库
        $res = $this->where('bookID', $data['bookID'])->find();
        if ($res) {
            $res['borrowingtime'] = $data['borrowingtime'];
            $res['returntime'] = $data['returntime'];
            $res['renewstatus'] = $data['renewstatus'];
            $res['returnstatus'] = $data['returnstatus'];
            $res['time'] = $data['time'];
            $res->save();
            return 1;
        }
    }
}