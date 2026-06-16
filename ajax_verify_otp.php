<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);

    if (isset($_SESSION['live_signup_otp']) && $entered_otp == $_SESSION['live_signup_otp']) {
        $_SESSION['is_email_verified'] = true; 
        unset($_SESSION['live_signup_otp']); 
        echo json_encode(['success' => true]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect OTP! Try again.']);
        exit();
    }
}
?>