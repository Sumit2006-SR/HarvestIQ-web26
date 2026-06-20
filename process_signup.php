<?php
session_start();  
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     if(!isset($_SESSION['is_email_verified']) || $_SESSION['is_email_verified'] !== true) {
        echo "<script>alert('Error: Email not verified!'); window.location.href='index.php';</script>";
        exit();
    }

    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_SESSION['live_signup_email']); 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (full_name, email, password, role) VALUES ('$name', '$email', '$password', 'farmer')";
    
    if (mysqli_query($conn, $query)) {
        $new_user_id = mysqli_insert_id($conn);

        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['full_name'] = $name;
        $_SESSION['role'] = 'farmer';  
        
        unset($_SESSION['live_signup_email']);
        unset($_SESSION['is_email_verified']);
        
        echo "<script>window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Database Error!'); window.location.href='index.php';</script>";
    }
}
?>