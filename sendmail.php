<?php
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

require '/home/levoncodes/env.php';

$name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8');

if (!$email || !$name || !$message) {
    die('Invalid input data.');
}

$mail = new PHPMailer();
try {
    $mail->isSMTP();
    $mail->SMTPDebug = 0; // Disable debugging for production
    $mail->SMTPAuth = true;
    $mail->Timeout = 30;
    $mail->SMTPSecure = getenv('SMTP_SECURE') ?: 'ssl';
    $mail->Port = getenv('SMTP_PORT') ?: 465;
    $mail->Host = getenv('SMTP_HOST') ?: 'mail.levon.codes';
    $mail->Username = getenv('SMTP_USERNAME');
    $mail->Password = getenv('SMTP_PASSWORD');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(getenv('SMTP_USERNAME'), 'Levon Codes');
    $mail->addAddress('levon.s.avetisyan@gmail.com');
    $mail->Subject = 'Message from levon.codes contact form';

    // Updated email body and alt body
    $mail->Body = "Email: {$email}<br>Name: {$name}<br>Message: {$message}";
    $mail->AltBody = "Email: {$email}\nName: {$name}\nMessage: {$message}";

    // Handle attachments
    if (!empty($_FILES['userfile']['tmp_name'][0])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        for ($ct = 0; $ct < count($_FILES['userfile']['tmp_name']); $ct++) {
            $tmpName = $_FILES['userfile']['tmp_name'][$ct];
            $fileName = basename($_FILES['userfile']['name'][$ct]);
            $fileMimeType = finfo_file($finfo, $tmpName);

            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $maxFileSize = 2 * 1024 * 1024;

            if (in_array($fileMimeType, $allowedTypes) && filesize($tmpName) <= $maxFileSize) {
                $mail->addAttachment($tmpName, $fileName);
            } else {
                echo "Invalid file: {$fileName}";
            }
        }
        finfo_close($finfo);
    }

    if (!$mail->send()) {
        throw new Exception('Mail not sent: ' . $mail->ErrorInfo);
    }

    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
