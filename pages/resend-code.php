<?php
session_start();

// Redirect if no temporary user data exists
if (!isset($_SESSION['temp_user'])) {
    header('Location: /KMSC/pages/register.php');
    exit;
}

// Rate Limiting: Allow only 3 resend attempts within 10 minutes
if (!isset($_SESSION['resend_attempts'])) {
    $_SESSION['resend_attempts'] = 1;
    $_SESSION['resend_last_attempt'] = time();
} else {
    $timeSinceLastAttempt = time() - $_SESSION['resend_last_attempt'];
    if ($timeSinceLastAttempt < 600) { // 10 minutes
        $_SESSION['resend_attempts']++;
        if ($_SESSION['resend_attempts'] > 3) {
            $_SESSION['error'] = 'Too many resend attempts. Please try again later.';
            header('Location: /KMSC/pages/verify.php');
            exit;
        }
    } else {
        // Reset attempts if the time window has passed
        $_SESSION['resend_attempts'] = 1;
        $_SESSION['resend_last_attempt'] = time();
    }
}

// Regenerate the verification code
$verificationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
$_SESSION['verification_code'] = $verificationCode;
$_SESSION['verification_code_expiry'] = time() + 600; // 10 minutes from now

// Include PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Send the new code via email
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP(); // Use SMTP
    $mail->Host = 'sandbox.smtp.mailtrap.io'; // Replace with your SMTP server
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = '6039652b84e6e8'; // Replace with your email
    $mail->Password = 'aa925e50fb8a5d'; // Replace with your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587; // TCP port to connect to

    // Recipients
    $mail->setFrom('no-reply@kmsc.com', 'KMSC'); // Sender email and name
    $mail->addAddress($_SESSION['temp_user']['email']); // Recipient email

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Your New Verification Code';
    $mail->Body = '
        <html>
        <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
            <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
                <h2 style="color: #333333; text-align: center;">Welcome to KMSC!</h2>
                <p style="color: #555555;">Your new verification code is: <b style="color: #dc2626;">' . $verificationCode . '</b></p>
                <p style="color: #555555;">This code will expire in 10 minutes.</p>
                <p style="color: #555555;">If you did not request this, please ignore this email.</p>
                <p style="text-align: center; margin-top: 20px;">
                    <a href="https://kmsc.com" style="background-color: #dc2626; color: #ffffff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Visit KMSC</a>
                </p>
            </div>
        </body>
        </html>
    ';

    // Send the email
    $mail->send();

    // Calculate remaining attempts
    $remainingAttempts = 3 - $_SESSION['resend_attempts'];

    // Set success message
    $_SESSION['success'] = "Verification code was resent. Please check your email. You have $remainingAttempts attempts left.";

    // Redirect back to the verification page
    header('Location: /KMSC/pages/verify.php');
    exit;
} catch (Exception $e) {
    // Log the error and display a message
    error_log('Email sending error: ' . $mail->ErrorInfo);
    $_SESSION['error'] = 'Failed to resend the verification email. Please try again.';
    header('Location: /KMSC/pages/verify.php');
    exit;
}
?>