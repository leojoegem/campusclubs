<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Generate a 6-digit random 2FA code
$twoFactorCode = rand(100000, 999999);

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'leojoegem@gmail.com';  // Your email
    $mail->Password = 'adsv bzob lynp pitx';  // Your email app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('leojoegem@gmail.com', 'Josalah');
    $mail->addAddress('leo.lj45@gmail.com', 'Leo Joe');  // Recipient's email

    $mail->isHTML(true);
    $mail->Subject = 'Your 2FA Code';
    
    // Include the 2FA code in the email body
    $mail->Body = "Your 2FA code is: <b>$twoFactorCode</b>";

    $mail->send();
    echo '2FA Code has been sent successfully.';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
