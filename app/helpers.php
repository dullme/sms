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
