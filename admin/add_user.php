<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$alert_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_user'])) {
    $full_name = mysqli_real_escape_string($conn, trim($_POST['full_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $raw_password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $send_email = isset($_POST['send_email']) ? true : false;

    $check_query = "SELECT id FROM users WHERE email = '$email'";
    if (mysqli_num_rows(mysqli_query($conn, $check_query)) > 0) {
        $alert_message = '<div class="alert alert-danger shadow-sm"><i class="fa-solid fa-circle-exclamation me-2"></i> This email is already registered!</div>';
    } else {
        $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO users (full_name, email, password, role, is_verified) VALUES ('$full_name', '$email', '$hashed_password', '$role', 1)";

        if (mysqli_query($conn, $insert_query)) {
            $email_status_text = "(No email sent).";

            if ($send_email) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'sumitrudra02@gmail.com';
                    $mail->Password   = 'cevmqkncfjjabdgx';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
                    $mail->setFrom('sumitrudra02@gmail.com', 'HarvestIQ');
                    $mail->addAddress($email, $full_name);
                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to HarvestIQ! Your Login Credentials Inside';
                    $mail->Body = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 30px; border: 1px solid #E2E8F0; border-radius: 16px;'>
                        <h2 style='color: #16a34a;'>Welcome to HarvestIQ!</h2>
                        <p>Hi $full_name, your account is ready.</p>
                        <p><strong>Email:</strong> $email<br><strong>Password:</strong> $raw_password<br><strong>Role:</strong> $role</p>
                    </div>";
                    $mail->send();
                    $email_status_text = "and a welcome email with credentials has been sent.";
                } catch (Exception $e) {
                    $email_status_text = "but the email failed to send.";
                }
            }

            $alert_message = '<div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i> User successfully added ' . $email_status_text . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Farmer | HarvestIQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

<?php require 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-page-wrap">
        <a href="manage_users.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Manage Farmers</a>
        <div class="mb-4">
            <h2 class="page-title">Add New Farmer</h2>
            <p class="text-muted mt-1 fw-medium">Provision a new account securely for the HarvestIQ platform.</p>
        </div>

        <?php echo $alert_message; ?>

        <div class="form-card">
            <form action="add_user.php" method="POST">
                <h5 class="fw-bold mb-3 brand-font">User Information</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Full Name</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-user icon-left"></i>
                            <input type="text" name="full_name" class="form-control-custom" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email Address</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-envelope icon-left"></i>
                            <input type="email" name="email" class="form-control-custom" required>
                        </div>
                    </div>
                </div>

                <h5 class="fw-bold mt-2 mb-3 brand-font">Security & Permissions</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Account Password</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-lock icon-left"></i>
                            <input type="text" name="password" id="passwordField" class="form-control-custom" required minlength="8">
                            <button type="button" class="btn-generate" onclick="generatePassword()">Generate</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Assign Role</label>
                        <div class="input-group-custom">
                            <i class="fa-solid fa-shield-halved icon-left"></i>
                            <select name="role" class="form-select-custom" required>
                                <option value="farmer" selected>Farmer</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="switch-container mt-2">
                    <div>
                        <div class="fw-bold">Send Welcome Email</div>
                        <div class="text-muted small">Automatically email the user with their login credentials.</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input fs-4" type="checkbox" name="send_email" checked>
                    </div>
                </div>

                <button type="submit" name="add_user" class="btn-submit mt-2"><i class="fa-solid fa-user-plus me-2"></i> Create Account</button>
            </form>
        </div>
    </div>
</div>

<script>
function generatePassword() {
    var chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
    var password = '';
    for (var i = 0; i < 10; i++) password += chars.charAt(Math.floor(Math.random() * chars.length));
    document.getElementById('passwordField').value = password;
}
</script>
</body>
</html>
