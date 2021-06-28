<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/24
 * Time: 16:08
 */

namespace app\common\model;


use think\Model;

class Ticket extends Model
{
    //Reader
    //通过id获取某条件的罚款
    public function getByID($flag)
    {
        $reader=model('reader')->findByName(session('userid'));
        $res=$this->alias("t")->join('tickettype tt','t.finetypeID=tt.finetypeID','left')->
        where('userid',$reader['userid'])->where('settlestatus',$flag)->select();
        return $res;
    }
    //根据id获取全部罚单
    public function getAll()
    {
        $reader=model('reader')->findByName(session('userid'));
        $res=$this->alias("t")->join('tickettype tt','t.finetypeID=tt.finetypeID','left')->
        where('userid',$reader['userid'])->select();
        return $res;
    }
    //根据id获取某一罚单
    public function getOne($id)
    {
        $res=$this->alias("t")->join('tickettype tt','t.finetypeID=tt.finetypeID','left')->
        where('fineid',$id)->find();
        return $res;
    }
    //支付
    public function finish($id)
    {
        $this->where('fineid',$id)
            ->update(['settlestatus'=>'1']);
    }
    //超时获惩
    public function overTime($data)
    {
        $this->insert($data);
    }



    //Admin
    //修改罚单记录
    public function editticket($data)
    {
        //检查
        $validate = new \app\common\validate\Ticket();
        if ($validate->scene('editticket')->check($data) == 0) {
            return $validate->getError();
        }

        //数据库
        $res = $this->where('fineid', $data['fineid'])->find();
        if ($res) {
            $res['finetypeID'] = $data['finetypeID'];
            $res['totalfineprice'] = $data['totalfineprice'];
            $res['finedetail'] = $data['finedetail'];
            $res['settlestatus'] = $data['settlestatus'];
            $res->save();
            return 1;
        }
    }
}