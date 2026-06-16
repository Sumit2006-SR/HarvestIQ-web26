<?php
// যেকোনো PHP Warning বন্ধ করে দেওয়া হলো যাতে JSON না ভাঙে
error_reporting(0);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer ফাইল লোড করা (src ফোল্ডার থাকলে সেটা থেকে নেবে)
try {
    if (file_exists('PHPMailer/src/Exception.php')) {
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
    } else {
        require 'PHPMailer/Exception.php';
        require 'PHPMailer/PHPMailer.php';
        require 'PHPMailer/SMTP.php';
    }
} catch (Throwable $t) {
    echo json_encode(['success' => false, 'message' => 'PHPMailer files missing!']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    
     $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    
    if (!$check) {
         echo json_encode(['success' => false, 'message' => 'Database Error: ' . mysqli_error($conn)]);
        exit();
    }

    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered! Login instead.']);
        exit();
    }

    $otp = rand(100000, 999999);
    $_SESSION['live_signup_otp'] = $otp;
    $_SESSION['live_signup_email'] = $email;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sumitrudra02@gmail.com';  
        $mail->Password   = 'cevmqkncfjjabdgx';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // XAMPP Localhost SSL Bypass
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('sumitrudra02@gmail.com', 'HarvestIQ');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your HarvestIQ Verification Code';
        
        $mail->Body    = "<div style='font-family: sans-serif; text-align: center; padding: 30px; background-color: #f8fafc; border-radius: 10px;'>
                            <h2 style='color: #16a34a;'>Welcome to HarvestIQ! 🌱</h2>
                            <p style='color: #475569; font-size: 16px;'>Here is your secure verification code:</p>
                            <h1 style='background: #f0fdf4; padding: 15px 30px; letter-spacing: 8px; color: #15803d; border: 2px solid #bbf7d0; border-radius: 12px; display: inline-block; font-size: 32px;'>$otp</h1>
                            <p style='color: #64748b; font-size: 14px; margin-top: 20px;'>Cultivating Smarter Decisions, Yielding Better Futures.</p>
                          </div>";

        $mail->send();
        echo json_encode(['success' => true]);
        exit();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'SMTP Error: Please check internet connection.']);
        exit();
    }
}
?>