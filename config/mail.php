<?php
return [
    // 邮箱配置
    'Host'         => 'smtp.qq.com',        //smtp服务器的名称
    'Username'     => '371976974@qq.com',   //你的邮箱名
    'Password'     => '',                   //邮箱密码(QQ邮箱密码为授权码)
    'SMTPAuth'     => true,                 //启用SMTP身份验证
    'SMTPSecure'   => 'ssl',                //启用SSL/TLS加密
    'Port'         => 465,                  //端口号
    'From'         => '371976974@qq.com',   //发件人地址
    'FROMName'     => 'My小站系统通知',    //发件人名称
    'CharSet'      => 'utf-8',              //编码
    'SMTPOptions'  => [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ],
];