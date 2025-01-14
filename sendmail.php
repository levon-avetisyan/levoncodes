<?php
// Файлы PHPMailer
// PHPMailer
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/Exception.php';
// Variables
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
// Settings
use PHPMailer\PHPMailer\PHPMailer;
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Mailer = "smtp";
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;
$mail->Host = 'smtp.gmail.com';
$mail->Username = "mailer@levon.codes";
$mail->Password = "28kI])W8Twp1";
$mail->isHTML(true);
$mail->CharSet = 'UTF-8';
$mail->setFrom('mailer@levon.codes');
$mail->addAddress('levon.s.avetisyan@gmail.com');
$mail->isHTML(true);
$mail->Subject = 'Message from levon.codes contact form';
$mail->Body = $message;
$mail->AltBody = "This message uses plain text!";
// File attachment
if (array_key_exists('userfile', $_FILES)) {
    // Create a message
    $mail = new PHPMailer;
    $mail->setFrom('mailer@levon.codes');
    $mail->addAddress('levon.s.avetisyan@gmail.com');
    $mail->Subject = 'Message from levon.codes contact form';
    $mail->Body = "Message: {$message} Name: {$name} Email: {$email}";
    $mail->AltBody = "This message uses plain text!";
    
    // Attach multiple files one by one
    for ($ct = 0; $ct < count($_FILES['userfile']['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), hash('sha256', $_FILES['userfile']['name'][$ct]));
        $filename = $_FILES['userfile']['name'][$ct];
        if (move_uploaded_file($_FILES['userfile']['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
        } else {
//            $msg .= 'Failed to move file to ' . $uploadfile;
        }
    }
    if (!$mail->send()) {
        $msg .= "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $msg .= "ok";
    }
}
?>