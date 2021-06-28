<?php

namespace app\common\validate;

use think\Validate;

class Bookdescribe extends Validate{
    //规则
    protected  $rule = [
        'ISBN|图书ISBN' => 'require',
        'sonID|序列ID' => 'require',
        'publicID|出版商' => 'require',
        'borrowtypeID|图书种类' => 'require',
        'bookname|书名' => 'require',
        'price|价格' => 'require|float',
        'introduce|介绍' => 'require',
        'picture|图片' => 'require',
    ];

    //场景
    protected $scene = [
        'addbookdescirbe'=>[
            'ISBN','sonID','publicID','borrowtypeID','bookname','price','introduce','picture'
        ],
        'editbookdescirbe'=>[
            'sonID','publicID','borrowtypeID','bookname','price','introduce','picture'
        ],
    ];
}
