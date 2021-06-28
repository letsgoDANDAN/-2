<?php
namespace app\admin\controller;

use app\common\model\Borrow;
use app\common\model\Ticket;
use think\Controller;

class Admin extends Controller{
    //显示所有图书
    public function ashowbooks()
    {
        $list=model("Bookdescribe")->paginate(5);
        $this->assign('booklist',$list);
        return $this->fetch();
    }

    //搜索有关图书
    public function asearchbook()
    {
        if(true)
        {
            $key=input('key');
            if($key!=null)
            {
                session('key',$key);
            }else{
                $key= session('key');
            }
            $res=model('bookdescribe')->searchBook($key);
            $this->assign('booklist',$res);
        }
        return $this->fetch();
    }

    //重置
    public function areset()
    {
        if(request()->isAjax())
        {
            session('key',null);
        }
        return ['code'=>'1','msg'=> ''];
    }

    //显示某本图书的实体书
    public function ashowbook()
    {
        $data=[
            'ISBN'=> input('ISBN')
        ];
        $res=model('Book')->getBooks($data['ISBN']);
        $this->assign('booklist',$res);
        return view();
    }

    //显示用户
    public function ashowreader()
    {
        $res=\app\common\model\Reader::all();
        $this->assign('readerlist',$res);
        return view();
    }

    //显示借阅记录
    public function ashowborrow()
    {
        $res=Borrow::all();
        $this->assign('borrowlist',$res);
        return view();
    }

    //显示罚单
    public function ashowticket()
    {
        $res=Ticket::all();
        $this->assign('ticketlist',$res);
        return view();
    }

    //增加图书描述
    public function  addbookdescribe()
    {
        if(request()->isAjax()){
            $data=[
                'ISBN'=>input('ISBN'),
                'sonID'=>input('sonID'),
                'publicID'=>input('publicID'),
                'borrowtypeID'=>input('borrowtypeID'),
                'bookname'=>input('bookname'),
                'price'=>input('price'),
                'introduce'=>input('introduce'),
                'picture'=>input('picture'),
            ];
            $res=model('Bookdescribe')->addbookdescribe($data);
            if($res==1){
                $this->success('添加图书描述成功','admin/admin/ashowbooks');
            }else{
                $this->error($res);
            }
        }
        return view();
    }

    //删除图书描述
    public function  deletebookdescribe(){
        $data=[
            'ISBN'=>input('ISBN'),
        ];
        model('Bookdescribe')->where('ISBN',$data['ISBN'])->delete();
        $this->success('删除图书描述成功','admin/admin/ashowbooks');
    }

    //编辑图书描述
    public function editbookdescribe(){
        if(request()->isAjax()){
            $data=[
                'ISBN'=>input('ISBN'),
                'sonID'=>input('sonID'),
                'publicID'=>input('publicID'),
                'borrowtypeID'=>input('borrowtypeID'),
                'bookname'=>input('bookname'),
                'price'=>input('price'),
                'introduce'=>input('introduce'),
                'picture'=>input('picture'),
            ];
            $res = model('Bookdescribe')->editbookdescribe($data);
            if($res==1){
                $this->success('编辑图书描述成功','admin/admin/ashowbooks');
            }else{
                $this->error($res);
            }
        }

        $data=[
            'ISBN'=>input('ISBN'),
        ];
        $onebookdescirbe=model('Bookdescribe')->where('ISBN',$data['ISBN'])->find();
        $this->assign('onebookdescribe',$onebookdescirbe);
        return view();
    }

    //增加实体书
    public function  addbook()
    {
        if(request()->isAjax()){
            $data=[
                'bookID'=>input('bookID'),
                'ISBN'=>input('ISBN'),
                'branchID'=>input('branchID'),
                'readerstatus'=>input('readerstatus'),
                'details'=>input('details'),
            ];
            $res=model('Book')->addbook($data);
            if($res==1){
                $this->success('添加图书成功','admin/admin/ashowbooks');
            }else{
                $this->error($res);
            }
        }
        return view();
    }

    //删除图书
    public function  deletebook(){
        $data=[
            'bookID'=>input('bookID'),
        ];
        model('Book')->where('bookID',$data['bookID'])->delete();
        $this->success('删除图书成功','admin/admin/ashowbooks');
    }

    //编辑图书
    public function editbook(){
        if(request()->isAjax()){
            $data=[
                'bookID'=>input('bookID'),
                'ISBN'=>input('ISBN'),
                'branchID'=>input('branchID'),
                'readerstatus'=>input('readerstatus'),
                'details'=>input('details'),
            ];
            $res = model('Book')->editbook($data);
            if($res==1){
                $this->success('编辑图书成功','admin/admin/ashowbooks');
            }else{
                $this->error($res);
            }
        }

        $data=[
            'bookID'=>input('bookID'),
        ];
        $onebook=model('Book')->where('bookID',$data['bookID'])->find();
        $this->assign('onebook',$onebook);
        return view();
    }

    //更新借阅记录
    public function editborrow(){
        if(request()->isAjax()){
            $data=[
                'userid'=>input('userid'),
                'bookID'=>input('bookID'),
                'borrowingtime'=>input('borrowingtime'),
                'returntime'=>input('returntime'),
                'renewstatus'=>input('renewstatus'),
                'returnstatus'=>input('returnstatus'),
                'time'=>input('time'),
            ];
            $res = model('Borrow')->editborrow($data);
            if($res==1){
                $this->success('更新借阅记录成功','admin/admin/ashowborrow');
            }else{
                $this->error($res);
            }
        }

        $data=[
            'bookID'=>input('bookID'),
        ];
        $oneborrow=model('Borrow')->where('bookID',$data['bookID'])->find();
        $this->assign('oneborrow',$oneborrow);
        return view();
    }

    //更新罚单记录
    public function editticket(){
        if(request()->isAjax()){
            $data=[
                'fineid'=>input('fineid'),
                'finetypeID'=>input('finetypeID'),
                'userid'=>input('userid'),
                'totalfineprice'=>input('totalfineprice'),
                'finedetail'=>input('finedetail'),
                'settlestatus'=>input('settlestatus'),
            ];
            $res = model('Ticket')->editticket($data);
            if($res==1){
                $this->success('更新罚单记录成功','admin/admin/ashowticket');
            }else{
                $this->error($res);
            }
        }

        $data=[
            'fineid'=>input('fineid'),
        ];
        $oneticket=model('Ticket')->where('fineid',$data['fineid'])->find();
        $this->assign('oneticket',$oneticket);
        return view();
    }
}