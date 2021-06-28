<?php
/**
 * Created by PhpStorm.
 * User: DanDan
 * Date: 2021/1/20
 * Time: 23:46
 */
namespace app\admin\controller;
use function Sodium\add;
use think\Controller;
use app\common\model;
class Reader extends Controller
{
    //显示所有图书
    public function showbooks()
    {
        $treelist=model('Booktype')->getFather();
        $list=model("Bookdescribe")->paginate(5);
        $this->assign('tree',$treelist);
        $this->assign('booklist',$list);
        return $this->fetch();
    }
    //显示某本图书的描述
    public function showbook()
    {
        $data=[
            'ISBN'=> input('ISBN')
        ];
        $res=model('Bookdescribe')->findByISBN($data['ISBN']);
        $typename=model('Booktype')->getBysonID($res['sonID']);
        $authorid=model('Writes')->getAuthorID($data['ISBN']);
        $authorname="";
        foreach($authorid as $id)
        {
            $idres=model('Author')->getAuthorName($id['authorID']);
            $authorname=$authorname." ".$idres['authorname'];
        }
        $publicname=model('Press')->getPressName($res['publicID']);
        $borrowtype=model('Borrowtype')->getBorrowTypeName($res['borrowtypeID']);
        $name=session('userid');
        $reader=model('Reader')->findByName($name);
        $data2=[
            'readertypeID'=> $reader['readertypeID'],
                'borrowtypeID'=> $res['borrowtypeID'],
        ];
        $borrowcondition=model('Borrowconditions')->selectByType($data2);
        $this->assign('borrowcondition',$borrowcondition);
        $this->assign('borrowtype',$borrowtype);
        $this->assign('press',$publicname);
        $this->assign('author',$authorname);
        $this->assign('booktype',$typename);
        $this->assign('book',$res);
        return view();
    }
    //根据关键词搜索有关图书
    public function searchbook()
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
            $res=model('Bookdescribe')->searchBook($key);
            $list=array();
            $treelist=$this->searchlist($key,$list);
            $treelist=array_reverse($treelist);
            $sonlist=model('Booktype')->getSon($key);
            $this->assign('son',$sonlist);
            $this->assign('tree',$treelist);
            $this->assign('booklist',$res);
        }
        return $this->fetch();
    }
    //清空搜索栏
    public function reset()
    {
        if(request()->isAjax())
        {
            session('key',null);
        }
        return ['code'=>'1','msg'=> ''];
    }
    //显示个人信息
    public function showreader()
    {
        $name=session('userid');
        $res=model('Reader')->findByName($name);
        $typename=model('Readertype')->getReaderTypeName($res['readertypeID']);
        $unitname=model('Unit')->getUnit($res['unitID']);
        $this->assign('unit',$unitname);
        $this->assign('type',$typename);
        $this->assign('reader',$res);
        return view();
    }
    //显示符合条件的借阅信息
    public function borrow()
    {
        $key=input('key');
        $name=session('userid');
        $reader=model('Reader')->findByName($name);
        if($key!='3'&&$key!=null)
        {
            if($key=='2')
            {
                $data=[
                    'returnstatus'=>'0',
                    'userid'=>$reader['userid']
                ];
            }else{
                $data=[
                    'returnstatus'=>$key,
                    'userid'=>$reader['userid']
                ];
            }
        }else{
            $data=[
                'userid'=>$reader['userid']
            ];
        }
        if($key!='2') {
            $res = model('Borrow')->getBorrowByID($data);
        }else{
            $res = model('Borrow')->getBorrowByIDOverTime($data);
        }
        $time=date('Y-m-d H:i:s');
        $this->assign('time',$time);
        $this->assign('borrowlist',$res);
        return view();
    }
    //查询罚单信息
    public function showticket()
    {
        $key=input('key');
        if($key=='3'||$key==null)
        {
            $res=model('Ticket')->getAll();
        }else{
            $res=model('Ticket')->getByID($key);
        }
        $this->assign('ticketlist',$res);
        return view();
    }
    //进入面借阅界
    public function borrowbook()
    {
        return view();
    }
    //进入借阅确认界面
    public function borrowbookjudge()
    {
        //变量tip用来提示用户是否可以借阅
        $tip=null;
        //对1、3、4三种情况进行判断
        $id=input('bookID');
        $reader=model('Reader')->findByName(session('userid'));
        $ticket=model('Ticket')->getByID('0');
        $book=model('Book')->getBook($id);
        $bd=model('Bookdescribe')->findByISBN($book['ISBN']);
        $typename=model('Booktype')->getBysonID($bd['sonID']);
        $authorid=model('Writes')->getAuthorID($bd['ISBN']);
        $authorname="";
        foreach($authorid as $id)
        {
            $idres=model('Author')->getAuthorName($id['authorID']);
            $authorname=$authorname." ".$idres['authorname'];
        }
        $publicname=model('Press')->getPressName($bd['publicID']);
        $data=[
            'readertypeID'=>$reader['readertypeID'],
            'borrowtypeID'=>$bd['borrowtypeID']
        ];
        $bc=model('Borrowconditions')->selectByType($data);
        if($reader['borrowstatus']=='0')
        {
            $tip='您处于黑名单中！';
        }else{
            if(count($ticket)>0)
            {
                $tip='您有罚单未处理！';
            }else{

                if($book['readerstatus']=='0')
                {
                    $tip='该书正被借阅中！';
                }else{
                    if($bd['borrowtypeID']=='BT01')
                    {
                        (int)$sum=(int)$bc['borrownum']-(int)$reader['cbooknumber'];
                    }else if($bd['borrowtypeID']=='BT02'){
                        (int)$sum=(int)$bc['borrownum']-(int)$reader['fbooknumber'];
                    }else
                    {
                        (int)$sum=(int)$bc['borrownum']-(int)$reader['booknumber']+(int)$reader['fbooknumber']+(int)$reader['cbooknumber'];
                    }
                    if($sum<=0)
                    {
                        $tip='您的借阅数量达到上限！';
                    }
                }
            }
        }
        //返回书籍信息，将2的判断交给用户
        $this->assign('tip',$tip);
        $this->assign('b',$book);
        $this->assign('book',$bd);
        $this->assign('bc',$bc);
        $this->assign('reader',$reader);
        $this->assign('press',$publicname);
        $this->assign('author',$authorname);
        $this->assign('booktype',$typename);
        return view();
    }
    //确定借书，返回通知页面
    public function borrownotice()
    {
        //获取各个数据，包括用户、实体书
        $id=input('ID');
        $reader=model('Reader')->findByName(session('userid'));
        $book=model('Book')->getBook($id);
        $bd=model('Bookdescribe')->findByISBN($book['ISBN']);
        $publicname=model('Library')->getByID($book['branchID']);
        $data=[
            'readertypeID'=>$reader['readertypeID'],
            'borrowtypeID'=>$bd['borrowtypeID']
        ];
        $bc=model('Borrowconditions')->selectByType($data);
        $time=$bc['borrowtime'];
        //更新实体书状态为不可借
        $b=[
            'readerstatus'=>'0',
            'details'=> '借阅中',
            'bookID'=>$book['bookID'],
        ];
        model('Book')->updateBook($b);
        //增添新的借阅信息
        if($bc['renewtime']!='0') {
            $borrow = [
                'userid' => $reader['userid'],
                'bookID' => $book['bookID'],
                'borrowingtime' => date('Y-m-d H:i:s'),
                'returntime' => date('Y-m-d H:i:s',strtotime('+'.$time.'day')),
                'renewstatus' => '1',
                'returnstatus'=>'0',
                'time'=>null,
            ];
        }else{
            $borrow = [
                'userid' => $reader['userid'],
                'bookID' => $book['bookID'],
                'borrowingtime' => date('Y-m-d H:i:s'),
                'returntime' => date('Y-m-d H:i:s',strtotime('+'.$time.'day')),
                'renewstatus' => '0',
                'returnstatus'=>'0',
                'time'=>null,
            ];
        }
        model('Borrow')->insertBorrow($borrow);
        //更新用户的借阅数量
        if($bd['borrowtypeID']=='BT01')
        {
            (int)$reader['cbooknumber']= 1+(int)$reader['cbooknumber'];
        }else if($bd['borrowtypeID']=='BT02'){
            (int)$reader['fbooknumber']= 1+(int)$reader['fbooknumber'];
        }
        (int)$reader['booknumber']=(int)$reader['booknumber']+1;
        model('Reader')->updateReader($reader);
        $this->assign('book',$book);
        $this->assign('borrow',$borrow);
        $this->assign('l',$publicname['branchname']);
        $this->assign('n',$bd['bookname']);
        return view();
    }
    //修改个人信息
    public function updatemyself()
    {
        $reader=model('Reader')->findByName(session('userid'));
        $unit=model('Unit')->select();
        $rt=model('Readertype')->select();
        $this->assign('reader',$reader);
        $this->assign('unit',$unit);
        $this->assign('rt',$rt);
        return view();
    }
    //保存个人信息
    public function doupdate()
    {
        $data=[
            'userid'=>input('userid'),
            'name'=>input('name'),
            'unitID'=>input('unitID'),
            'readertypeID'=>input('readertypeID'),
            'name'=>input('name'),
            'Email'=>input('Email'),
            'phone'=>input('phone'),
        ];
        model('Reader')->updateAll($data);
        $data2=[
            'userid'=>input('userid'),
            'name'=>input('name'),
            'name'=>input('name'),
            'Email'=>input('Email'),
            'phone'=>input('phone'),
        ];
        model('User')->updateUser($data2);
        session('userid',$data['name']);
        $this->redirect('admin/reader/showreader');
        return;
    }
    //支付罚金
    public function pay()
    {
        $id=input('ID');
        $res=model('Ticket')->getOne($id);
        $this->assign('t',$res);
        return view();
    }
    //确认支付
    public function paysuccess()
    {
        $id=input('ID');
        model('Ticket')->finish($id);
        $this->success('支付成功！','admin/reader/showticket');
        return;
    }
    //续借图书
    public function  renewbook()
    {
        $id=input('bookID');
        $bS=input('bS');
        $reader=model('Reader')->findByName(session('userid'));
        $book=model('Book')->getBook($id);
        $bd=model('Bookdescribe')->findByISBN($book['ISBN']);
        $data=[
            'readertypeID'=>$reader['readertypeID'],
            'borrowtypeID'=>$bd['borrowtypeID']
        ];
        $bc=model('Borrowconditions')->selectByType($data);
        $data2=[
            'userid'=>$reader['userid'],
            'bookID'=>$id,
            'returnstatus'=>$bS,
        ];
        $borrow=model('Borrow')->findOne($data2);
        $time=$bc['renewtime'];
        $borrow['returntime']=date('Y-m-d H:i:s',strtotime('+'.$time.' day',strtotime($borrow['returntime'])));
        model('Borrow')->updateReturntime($borrow);
        $this->assign('book',$book);
        $this->assign('borrow',$borrow);
        $this->assign('n',$bd['bookname']);
        $this->assign('time',$time);
        return view();
    }
    //归还图书
    public function returnbook()
    {
        $id=input('bookID');
        $bS=input('bS');
        $reader=model('Reader')->findByName(session('userid'));
        $book=model('Book')->getBook($id);
        $bd=model('Bookdescribe')->findByISBN($book['ISBN']);
        $data2=[
            'userid'=>$reader['userid'],
            'bookID'=>$id,
            'returnstatus'=>$bS,
        ];
        $borrow=model('Borrow')->findOne($data2);
        $authorid=model('Writes')->getAuthorID($book['ISBN']);
        $authorname="";
        foreach($authorid as $id)
        {
            $idres=model('Author')->getAuthorName($id['authorID']);
            $authorname=$authorname." ".$idres['authorname'];
        }
        $publicname=model('Press')->getPressName($bd['publicID']);

        $tip=null;
        $now=date('Y-m-d H:i:s');
        $returntime=$borrow['returntime'];
        if($now>$returntime)
        {
            $tip=intval((strtotime($now)-strtotime($returntime))/86400);
        }
        $this->assign('time',$tip);
        $this->assign('book',$book);
        $this->assign('borrow',$borrow);
        $this->assign('bd',$bd);
        $this->assign('author',$authorname);
        $this->assign('public',$publicname);
        return view();
    }
    //确认归还
    public function doreturn()
    {
        $id=input('ID');
        $reader=model('Reader')->findByName(session('userid'));
        $book=model('Book')->getBook($id);
        $bd=model('Bookdescribe')->findByISBN($book['ISBN']);
        $b=[
            'readerstatus'=>'1',
            'details'=> '可借阅',
            'bookID'=>$book['bookID'],
        ];
        model('Book')->updateBook($b);

        $data2=[
            'userid'=>$reader['userid'],
            'bookID'=>$id,
            'returnstatus'=>'0',
        ];
        $borrow=model('Borrow')->findOne($data2);
        $now=date('Y-m-d H:i:s');
        $borrow['time']=$now;
        model('Borrow')->finish($borrow);
        if($bd['borrowtypeID']=='BT01')
        {
            (int)$reader['cbooknumber']= (int)$reader['cbooknumber']-1;
        }else if($bd['borrowtypeID']=='BT02'){
            (int)$reader['fbooknumber']= (int)$reader['fbooknumber']-1;
        }
        $tip=null;
        $now=date('Y-m-d H:i:s');
        $returntime=$borrow['returntime'];
        if($now>$returntime) {
            $tip   = intval((strtotime($now) - strtotime($returntime)) / 86400);
            $money = $tip * 0.5;
            $data3 = [
                'fineid' => 'F' . $reader['userid'] . strtotime(date('Y-m-d H:i:s')),
                'finetypeID' => 'TT01',
                'userid' => $reader['userid'],
                'totalfineprice' => $money,
                'settlestatus' => '0',
                'finedetail' => '您在' . $borrow['borrowingtime'] . '借阅的图书逾期归还，需交纳' . $money . '元罚金！',
            ];
            model('Ticket')->overTime($data3);
        }
        (int)$reader['booknumber']=(int)$reader['booknumber']-1;
        model('Reader')->updateReader($reader);
        $this->success('归还成功！','admin/reader/borrow');
    }
    //寻找同一ISBN的实体书
    public function showbooklist()
    {
        $ISBN=input('ISBN');
        $res=model('Book')->getBooks($ISBN);
        $this->assign('booklist',$res);
        return view();
    }
    //显示借阅报表
    public function  borrowreport()
    {
        $time=input('time');
        $start=((int)$time-1).'-12-31 23:59:59';
        $start=strtotime($start);
        $start=date('Y-m-d H:i:s',$start);
        $end=$time.'-12-31 23:59:59';
        $end=strtotime($end);
        $end=date('Y-m-d H:i:s',$end);
        $reader=model('Reader')->findByName(session('userid'));
        $data=[
            'userid'=>$reader['userid'],
            'sT'=>$start,
            'eT'=>$end,
        ];
        $res=model('Borrow')->findByTime($data);
        $this->assign('borrow',$res);
        return view();
    }
    //中图查找链
    public function searchlist($id,$list)
    {
        $one=model('Booktype')->getBysonID($id);
        array_push($list,$one);
        if($one['fatherID']!=null)
        {
            return $this->searchlist($one['fatherID'],$list);
        }else{
            return $list;
        }
    }
}
