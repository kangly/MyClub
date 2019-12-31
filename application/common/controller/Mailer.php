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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Mailer extends Controller
{
    public function WelcomeUser($email='')
    {
        $title = '欢迎注册My小站会员！';
        $content = '您好：<br>
<p style="text-indent: 2em;">欢迎注册My小站会员！</p>
<p style="float: right;margin-top: 50px;">本邮件为系统邮件，请勿回复！</p>';
        $res = $this->send($email,$title,$content);
        echo $res;
    }

    /**
     * @param $to
     * @param $title
     * @param $content
     * @param string $cc
     * @param string $bcc
     * @param array $attachment
     * @return bool|string
     */
    public function send($to,$title,$content,$cc='',$bcc='',$attachment=[])
    {
        //简单记录邮件发送日志
        $log = new Logger('mailer');
        $log->pushHandler(new StreamHandler('log/mail_'.date('Ymd').'.log', Logger::INFO));

        $mail = new PHPMailer(true);
        $m = Config::pull('mail');

        //使用tls加密报错,改为使用ssl
        $mail->SMTPOptions = $m['SMTPOptions'];

        try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;        //启用详细调试
            $mail->isSMTP();                                //开启SMTP

            $mail->Host       = $m['Host'];                 //SMTP服务器
            $mail->SMTPAuth   = $m['SMTPAuth'];             //启用SMTP身份验证
            $mail->Username   = $m['Username'];             //SMTP用户名
            $mail->Password   = $m['Password'];             //SMTP密码(可能是授权码)
            $mail->SMTPSecure = $m['SMTPSecure'];           //启用TLS/SSL加密
            $mail->Port       = $m['Port'];                 //端口号
            $mail->CharSet    = $m['CharSet'];              //编码

            $mail->setFrom($m['From'],$m['FROMName']);      //发送人

            //收件人
            if(is_array($to)){
                foreach ($to as $v){
                    $mail->addAddress($v);
                }
            }else{
                $mail->addAddress($to);
            }

            $mail->addReplyTo($m['From'], $m['FROMName']);  //回复人

            //抄送人
            if($cc){
                if(is_array($cc)){
                    foreach ($cc as $v){
                        $mail->addCC($v);
                    }
                }else{
                    $mail->addCC($cc);
                }
            }

            //密送人
            if($bcc){
                if(is_array($bcc)){
                    foreach ($bcc as $v){
                        $mail->addBCC($v);
                    }
                }else{
                    $mail->addBCC($bcc);
                }
            }

            //附件
            if($attachment){
                foreach($attachment as $v){
                    if($v['path']){
                        $mail->addAttachment($v['path'],$v['name']?$v['name']:'');
                    }
                }
            }

            //内容
            $mail->isHTML(true);
            $mail->Subject = $title; //邮件标题
            $mail->Body    = $content; //邮件内容
            //$mail->AltBody = '如果邮件客户端不支持HTML则显示此内容';

            $mail->send();
            $log->info(sprintf("to：%s",$to).' send success');
            return 'send success';
        } catch (Exception $e) {
            $log->info(sprintf("to：%s",$to)." send error: {$mail->ErrorInfo}");
            return 'send error: '.$mail->ErrorInfo;
        }
    }
}