<?php
 require 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $entered_otp = trim($_POST['reset_otp']);

     if (isset($_SESSION['reset_email']) && isset($_SESSION['reset_otp'])) {
        
        $session_otp = $_SESSION['reset_otp'];
        
         $otp_expires = $_SESSION['reset_otp_expires'];
        if (time() > $otp_expires) {
            unset($_SESSION['reset_otp']);  
            unset($_SESSION['reset_otp_expires']);  
            
            echo "<script>alert('Your Reset OTP has expired! Please request a new one.'); window.location.href='index.php';</script>";
            exit();
        }

         if ($entered_otp == $session_otp) {
             $_SESSION['can_reset_password'] = true;
            
             echo "<script>window.location.href='index.php?show_new_pass_modal=true';</script>";
            exit();
        } else {
             echo "<script>alert('Incorrect OTP! Please check your email and try again.'); window.location.href='index.php?show_reset_otp_modal=true';</script>";
            exit();
        }
    } else {
         echo "<script>alert('Session expired or invalid request. Please request a password reset again.'); window.location.href='index.php';</script>";
        exit();
    }
}
?>