<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_name = trim($_POST['full_name'] ?? '');
        if ($new_name === '') {
            $error_msg = 'Full name cannot be empty.';
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ? WHERE id = ?");
            $stmt->bind_param('si', $new_name, $user_id);
            if ($stmt->execute()) {
                $_SESSION['full_name'] = $new_name;
                $success_msg = 'Profile updated successfully!';
            } else {
                $error_msg = 'Could not update profile. Please try again.';
            }
            $stmt->close();
        }
    }

    if (isset($_POST['update_password'])) {
        $current = $_POST['current_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';

        if (strlen($new_pass) < 8) {
            $error_msg = 'New password must be at least 8 characters.';
        } elseif ($new_pass !== $confirm_pass) {
            $error_msg = 'New passwords do not match.';
        } else {
            $pw_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $pw_stmt->bind_param('i', $user_id);
            $pw_stmt->execute();
            $row = $pw_stmt->get_result()->fetch_assoc();
            $pw_stmt->close();

            if ($row && password_verify($current, $row['password'])) {
                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param('si', $hashed, $user_id);
                if ($upd->execute()) {
                    $success_msg = 'Password updated successfully!';
                } else {
                    $error_msg = 'Could not update password.';
                }
                $upd->close();
            } else {
                $error_msg = 'Current password is incorrect.';
            }
        }
    }
}

$stmt = $conn->prepare("SELECT full_name, email, role, is_verified, created_at FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: logout.php');
    exit();
}

$full_name = $user['full_name'] ?: explode('@', $user['email'])[0];
$email = $user['email'];
$role = ucfirst($user['role'] ?? 'farmer');
$is_verified = (int) ($user['is_verified'] ?? 0);
$joined = !empty($user['created_at']) ? date('d M Y', strtotime($user['created_at'])) : 'N/A';

$name_parts = explode(' ', trim($full_name));
if (count($name_parts) >= 2) {
    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
} else {
    $initials = strtoupper(substr($full_name, 0, 1));
}

$avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($full_name) . '&background=16a34a&color=fff&bold=true&size=128';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HarvestIQ</title>
    <script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3.0">
    <script src="assets/js/theme.js" defer></script>
</head>
<body>

<?php include 'nav.php'; ?>

<main class="profile-page">
    <?php if ($success_msg): ?>
        <div class="hiq-alert hiq-alert-success glass-panel" style="padding:14px 18px;margin-bottom:1rem;"><i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>
    <?php if ($error_msg): ?>
        <div class="hiq-alert hiq-alert-error glass-panel" style="padding:14px 18px;margin-bottom:1rem;"><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <div class="profile-header-card glass-panel">
        <img src="<?php echo htmlspecialchars($avatar_url); ?>" alt="Avatar" class="profile-avatar">
        <div class="profile-meta">
            <h1><?php echo htmlspecialchars(ucwords($full_name)); ?></h1>
            <p class="email"><i class="fa-regular fa-envelope me-1"></i> <?php echo htmlspecialchars($email); ?></p>
            <?php if ($is_verified === 1): ?>
                <span class="verified-badge"><i class="fa-solid fa-circle-check"></i> Verified Farmer</span>
            <?php else: ?>
                <span class="verified-badge" style="background:rgba(148,163,184,0.12);border-color:rgba(148,163,184,0.3);color:var(--text-muted);"><i class="fa-regular fa-clock"></i> Verification Pending</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="profile-stats-row">
        <div class="profile-stat glass-panel">
            <div class="label">Joined Date</div>
            <div class="value"><i class="fa-regular fa-calendar me-1" style="color:var(--hiq-emerald);"></i><?php echo htmlspecialchars($joined); ?></div>
        </div>
        <div class="profile-stat glass-panel">
            <div class="label">Account Role</div>
            <div class="value"><i class="fa-solid fa-user-tag me-1" style="color:var(--hiq-emerald);"></i><?php echo htmlspecialchars($role); ?></div>
        </div>
        <div class="profile-stat glass-panel">
            <div class="label">User ID</div>
            <div class="value">#<?php echo $user_id; ?></div>
        </div>
        <div class="profile-stat glass-panel">
            <div class="label">Status</div>
            <div class="value" style="color:var(--hiq-emerald);">Active</div>
        </div>
    </div>

    <div class="profile-form-section glass-panel">
        <h2><i class="fa-solid fa-user-pen"></i> Account Settings</h2>
        <form method="POST">
            <div class="hiq-form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" class="hiq-input" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>
            <button type="submit" name="update_profile" class="btn-premium btn-primary-glow"><i class="fa-solid fa-floppy-disk"></i> Save Profile</button>
        </form>
    </div>

    <div class="profile-form-section glass-panel">
        <h2><i class="fa-solid fa-lock"></i> Change Password</h2>
        <form method="POST">
            <div class="hiq-form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="hiq-input" required autocomplete="current-password">
            </div>
            <div class="hiq-form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="hiq-input" required minlength="8" autocomplete="new-password">
            </div>
            <div class="hiq-form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="hiq-input" required minlength="8" autocomplete="new-password">
            </div>
            <button type="submit" name="update_password" class="btn-premium btn-outline-glow"><i class="fa-solid fa-key"></i> Update Password</button>
        </form>
    </div>
</main>

<?php include 'footer.php'; ?>

</body>
</html>
