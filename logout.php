<?php
// গ্লোবাল সিকিউরিটি কল
require 'db.php'; 

// ১. সেশনের সমস্ত ভেরিয়েবল মুছে ফেলা
$_SESSION = array();

// ২. ব্রাউজার থেকে সেশন কুকি পুরোপুরি ডিলিট করে দেওয়া (Enterprise Level Logout)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ৩. সার্ভার থেকে সেশন ধ্বংস করা
session_destroy();

// ৪. রিডাইরেক্ট করে হোমপেজে পাঠানো
header("Location: index.php");
exit();
?>