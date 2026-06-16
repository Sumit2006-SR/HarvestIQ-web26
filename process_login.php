<?php
require 'db.php';
header('Content-Type: application/json'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            
            if (password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; 
                $_SESSION['full_name'] = $user['full_name'];  
                $_SESSION['is_logged_in'] = true;
                
                 $redirect = ($user['role'] == 'admin') ? "admin/admin_dashboard.php" : "index.php";
                
                echo json_encode(['success' => true, 'redirect' => $redirect]);
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'Incorrect password! Please try again.']);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No account found with this email!']);
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database connection error!']);
        exit();
    }
}
?>