<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author:
// +----------------------------------------------------------------------

// 应用公共文件
//MD5加密
/**
 * 系统加密方法
 * @param string $data 要加密的字符串
 * @param string $key  加密密钥
 * @param int $expire  过期时间 单位 秒
 * return string
 * @author 冯宇亮 <www.gether.cn>
 */
function think_encrypt($data, $key = 'dadoasiojcioascinas1564894894assv.', $expire = 0) {
    $key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data = base64_encode($data);
    $x    = 0;
    $len  = strlen($data);
    $l    = strlen($key);
    $char = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    $str = sprintf('%010d', $expire ? $expire + time():0);
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
    }
    return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
}
/**
 * 系统解密方法
 * @param  string $data 要解密的字符串 （必须是think_encrypt方法加密的字符串）
 * @param  string $key  加密密钥
 * return string
 * @author 冯宇亮 <www.gether.cn>
 */
function think_decrypt($data, $key = 'dadoasiojcioascinas1564894894assv.'){
    $key    = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
    $data   = str_replace(array('-','_'),array('+','/'),$data);
    $mod4   = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    $data   = base64_decode($data);
    $expire = substr($data,0,10);
    $data   = substr($data,10);
    if($expire > 0 && $expire < time()) {
        return '';
    }
    $x      = 0;
    $len    = strlen($data);
    $l      = strlen($key);
    $char   = $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) $x = 0;
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }else{
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return base64_decode($str);
}

// 邮箱
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function mailto($to, $nickname, $title, $content)
{
    // 实例化
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                 // Enable verbose debug output   2调试模式 0用户模式
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet = 'utf-8';							// 设置右键格式编码
        $mail->Host = 'smtp.qq.com';  						// Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = '1225388159@qq.com';                 // SMTP username
        $mail->Password = 'atwumpjnoqecicjf';                           // SMTP password 坑点 坑点
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('1225388159@qq.com', '选课系统');	// 设置右键发送人信息(邮箱, 昵称)
        $mail->addAddress($to, $nickname);     // 设置收件人信息(邮箱, 昵称)
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');
//
//        //Attachments 附件
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $title;							// 设置发送的邮件标题
        $mail->Body    = $content;							// 设置邮件发送内容
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    } catch (Exception $e) {
        exception($mail->ErrorInfo,1001);					// 失败抛出错误信息
    }
}

// 把span替换成a标签
function replace ($data){
    return str_replace('span','a',$data);
}

