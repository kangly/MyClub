<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/11/19
 * Time: 11:56
 */
namespace app\common\controller;

use think\Controller;
use think\facade\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer extends Controller
{
    public function SendMailer($email='614797580@qq.com')
    {
        $mail = new PHPMailer(true);
        $mailC = Config::pull('mail');

        try {
            //设置
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;//启用详细调试
            $mail->SMTPDebug = SMTP::DEBUG_OFF;//关闭调试
            $mail->isSMTP();//开启SMTP发送
            $mail->Host       = $mailC['Host'];//SMTP服务器
            $mail->SMTPAuth   = true;//启用SMTP身份验证
            $mail->Username   = $mailC['Username'];//SMTP用户名
            $mail->Password   = $mailC['Password'];//SMTP密码(QQ邮箱是一个授权码)
            $mail->SMTPSecure = $mailC['SMTPSecure'];//启用TLS加密
            $mail->Port       = 587;//端口号

            $mail->setFrom($mailC['From'], $mailC['FROMName']);//发送
            $mail->addAddress($email, '测试接收人');//接收
            //$mail->addAddress('ellen@example.com');//添加其他接收人
            $mail->addReplyTo($mailC['From'], $mailC['FROMName']);//回复
            //$mail->addCC('cc@example.com');//抄送
            //$mail->addBCC('bcc@example.com');//密送

            //附件
            //$mail->addAttachment('/var/tmp/file.tar.gz');
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            //内容
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}