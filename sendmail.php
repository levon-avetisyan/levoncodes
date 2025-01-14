<?php
// Import PHPMailer classes
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/home/levoncodes/public_html/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

// Load environment variables
require '/home/levoncodes/env.php';

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
    $mail->SMTPDebug = 0; // Use 1 or 2 for debugging
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = getenv('SMTP_SECURE') ?: 'tls';
    $mail->Port = getenv('SMTP_PORT') ?: 587;
    $mail->Host = getenv('SMTP_HOST') ?: 'smtp.gmail.com';
    $mail->Username = getenv('SMTP_USERNAME');
    $mail->Password = getenv('SMTP_PASSWORD');
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    // Set email details
    $mail->setFrom(getenv('SMTP_USERNAME'), 'Levon Codes');
    $mail->addAddress('levon.s.avetisyan@gmail.com');
    $mail->Subject = 'Message from levon.codes contact form';
    $mail->Body = "Message: {$message}<br>Name: {$name}<br>Email: {$email}";
    $mail->AltBody = "Message: {$message}\nName: {$name}\nEmail: {$email}";

    // Handle file attachments
    if (!empty($_FILES['userfile']['tmp_name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); // Open file info resource
        for ($ct = 0; $ct < count($_FILES['userfile']['tmp_name']); $ct++) {
            $tmpName = $_FILES['userfile']['tmp_name'][$ct];
            $fileName = basename($_FILES['userfile']['name'][$ct]);

            // Validate file type and size
            $fileMimeType = finfo_file($finfo, $tmpName); // Get MIME type
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB

            if (in_array($fileMimeType, $allowedTypes) && filesize($tmpName) <= $maxFileSize) {
                $uploadFile = tempnam(sys_get_temp_dir(), hash('sha256', $fileName));
                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $mail->addAttachment($uploadFile, $fileName);
                }
            }
        }
        finfo_close($finfo); // Close file info resource
    }

    // Send email
    if (!$mail->send()) {
        throw new Exception('Mail not sent: ' . $mail->ErrorInfo);
    }

    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
