<?php


function initCode()
{
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d').substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
        $d = '',
        $f = 0;
        $f < 5;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return $d;
}

function getBank(){
    return [
        "中国工商银行",
        "中国建设银行",
        "中国农业银行",
        "中国银行",
        "交通银行",
        "招商银行",
        "中国邮政储蓄银行",
        "中信银行",
        "光大银行",
        "民生银行",
        "兴业银行",
        "华夏银行",
        "上海浦东发展银行",
        "深圳发展银行",
        "广东发展银行",
        "上海银行",
        "平安银行",
        "宁波银行",
        "杭州银行",
    ];
}

function oneDayMaxSendCount(){
    $list = config('one_day_max_send_count_list');

    return explode(',', $list);
}

function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);             //抽取随机数
        if ($randNum <= $proCur) {
            $result = $key;                         //得出结果
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}

function ClearHtml($str)
{
    $str = trim($str); //清除字符串两边的空格
    $str = strip_tags($str, ""); //利用php自带的函数清除html格式
    $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
    $str = preg_replace("/\r\n/", "", $str);
    $str = preg_replace("/\r/", "", $str);
    $str = preg_replace("/\n/", "", $str);
    $str = preg_replace("/ /", "", $str);
    $str = preg_replace("/  /", "", $str);  //匹配html中的空格

    return trim($str); //返回字符串
}