<?php
// Import PHPMailer classes
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// Load environment variables
require '/home/levoncodes/env.php'; // Updated path to env.php

// Validate and sanitize input
$name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8');

if (!$email || !$name || !$message) {
    die('Invalid input data.');
}

// Initialize PHPMailer
$mail = new PHPMailer();
try {
    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug = 2; // Debug level (0 = no output, 1 = commands, 2 = full)
    $mail->SMTPAuth = true;
    $mail->Timeout = 30; // Connection timeout
    $mail->SMTPSecure = getenv('SMTP_SECURE') ?: 'tls';
    $mail->Port = getenv('SMTP_PORT') ?: 465;
    $mail->Host = getenv('SMTP_HOST') ?: 'mail.levon.codes';
    $mail->Username = getenv('SMTP_USERNAME');
    $mail->Password = getenv('SMTP_PASSWORD');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(getenv('SMTP_USERNAME'), 'Levon Codes');
    $mail->addAddress('levon.s.avetisyan@gmail.com');
    $mail->Subject = 'Message from levon.codes contact form';
    $mail->Body = "Message: {$message}<br>Name: {$name}<br>Email: {$email}";
    $mail->AltBody = "Message: {$message}\nName: {$name}\nEmail: {$email}";

    if (!$mail->send()) {
        throw new Exception('Mail not sent: ' . $mail->ErrorInfo);
    }

    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>