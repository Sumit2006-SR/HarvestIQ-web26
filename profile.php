<?php
require 'db.php';

// Check if user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$toast_msg = "";
$toast_type = "";

// =========================================
// PROFILE UPDATE LOGIC
// =========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    
    $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
    $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $farm_size = isset($_POST['farm_size']) ? trim($_POST['farm_size']) : '';
    $crops_grown = isset($_POST['crops_grown']) ? trim($_POST['crops_grown']) : '';
    $experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
    
    $profile_image_path = null;

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        $file_name = $_FILES['profile_image']['name'];
        $file_tmp = $_FILES['profile_image']['tmp_name'];
        $file_size = $_FILES['profile_image']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Validate file
        if (!in_array($file_ext, $allowed_ext)) {
            $toast_msg = "Invalid image format. Please use JPG, PNG, or WEBP.";
            $toast_type = "error";
        } elseif ($file_size > 5 * 1024 * 1024) { // 5MB limit
            $toast_msg = "Image size must be less than 5MB.";
            $toast_type = "error";
        } else {
            // Create upload directory
            $upload_dir = "uploads/profiles/";
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate unique filename
            $new_file_name = "user_" . $user_id . "_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
            
            // Resize image if needed
            if (function_exists('getimagesize')) {
                $img_size = getimagesize($file_tmp);
                if ($img_size[0] > 2000 || $img_size[1] > 2000) {
                    // Image is too large, we'll just move it as is
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                        $profile_image_path = $upload_dir . $new_file_name;
                    } else {
                        $toast_msg = "Failed to upload image. Please try again.";
                        $toast_type = "error";
                    }
                } else {
                    if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                        $profile_image_path = $upload_dir . $new_file_name;
                    } else {
                        $toast_msg = "Failed to upload image. Please try again.";
                        $toast_type = "error";
                    }
                }
            } else {
                if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                    $profile_image_path = $upload_dir . $new_file_name;
                } else {
                    $toast_msg = "Failed to upload image. Please try again.";
                    $toast_type = "error";
                }
            }
        }
    }

    // Update database if no upload errors
    if (empty($toast_msg)) {
        if ($profile_image_path) {
            // Delete old profile image if exists
            $old_stmt = $conn->prepare("SELECT profile_image FROM users WHERE id = ?");
            $old_stmt->bind_param("i", $user_id);
            $old_stmt->execute();
            $old_result = $old_stmt->get_result()->fetch_assoc();
            $old_stmt->close();
            
            if ($old_result && !empty($old_result['profile_image']) && file_exists($old_result['profile_image'])) {
                unlink($old_result['profile_image']);
            }

            $update_stmt = $conn->prepare("UPDATE users SET full_name=?, bio=?, location=?, phone=?, farm_size=?, crops_grown=?, experience=?, profile_image=?, updated_at=NOW() WHERE id=?");
            $update_stmt->bind_param("ssssssssi", $full_name, $bio, $location, $phone, $farm_size, $crops_grown, $experience, $profile_image_path, $user_id);
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET full_name=?, bio=?, location=?, phone=?, farm_size=?, crops_grown=?, experience=?, updated_at=NOW() WHERE id=?");
            $update_stmt->bind_param("sssssssi", $full_name, $bio, $location, $phone, $farm_size, $crops_grown, $experience, $user_id);
        }

        if ($update_stmt->execute()) {
            $_SESSION['full_name'] = $full_name;
            $toast_msg = "Profile updated successfully!";
            $toast_type = "success";
        } else {
            $toast_msg = "Failed to update profile. Please try again.";
            $toast_type = "error";
        }
        $update_stmt->close();
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT id, full_name, email, role, profile_image, bio, location, phone, farm_size, crops_grown, experience, is_verified, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user_data) {
    header('Location: logout.php');
    exit();
}

// Format data
$full_name = htmlspecialchars($user_data['full_name'] ?? 'Farmer');
$email = htmlspecialchars($user_data['email']);
$role = ucfirst($user_data['role'] ?? 'farmer');
$bio = htmlspecialchars($user_data['bio'] ?? '');
$location = htmlspecialchars($user_data['location'] ?? '');
$phone = htmlspecialchars($user_data['phone'] ?? '');
$farm_size = htmlspecialchars($user_data['farm_size'] ?? '');
$crops_grown = htmlspecialchars($user_data['crops_grown'] ?? '');
$experience = htmlspecialchars($user_data['experience'] ?? '');
$is_verified = (int) ($user_data['is_verified'] ?? 0);
$profile_image = !empty($user_data['profile_image']) && file_exists($user_data['profile_image']) ? htmlspecialchars($user_data['profile_image']) : 'assets/default-avatar.png';
$joined = !empty($user_data['created_at']) ? date('d M Y', strtotime($user_data['created_at'])) : 'N/A';

// Get name initials
$name_parts = explode(' ', trim($user_data['full_name']));
if (count($name_parts) >= 2) {
    $initials = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
} else {
    $initials = strtoupper(substr($user_data['full_name'], 0, 1));
}

// Generate avatar if no profile image
$avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($user_data['full_name']) . '&background=16a34a&color=fff&bold=true&size=200';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | HarvestIQ</title>
    <script>
        (function(){
            try {
                const theme = localStorage.getItem('harvestiq-theme') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
            } catch(e) {}
        })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3.0">
    <script src="assets/js/theme.js" defer></script>
    <style>
        :root {
            --hiq-primary: #16a34a;
            --hiq-secondary: #059669;
            --hiq-accent: #10b981;
            --hiq-emerald: #10b981;
            --hiq-light: #f0fdf4;
            --hiq-dark: #0f172a;
            --hiq-gray-50: #f9fafb;
            --hiq-gray-100: #f3f4f6;
            --hiq-gray-200: #e5e7eb;
            --hiq-gray-300: #d1d5db;
            --hiq-gray-400: #9ca3af;
            --hiq-gray-500: #6b7280;
            --hiq-gray-600: #4b5563;
            --hiq-gray-700: #374151;
            --hiq-text-main: #111827;
            --hiq-text-soft: #6b7280;
            --hiq-bg: #ffffff;
            --hiq-surface: #ffffff;
            --hiq-border: #e5e7eb;
        }

        [data-theme="dark"] {
            --hiq-text-main: #f9fafb;
            --hiq-text-soft: #d1d5db;
            --hiq-bg: #0f172a;
            --hiq-surface: #1e293b;
            --hiq-border: #334155;
            --hiq-light: #064e3b;
            --hiq-gray-50: #1e293b;
            --hiq-gray-100: #334155;
            --hiq-gray-200: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--hiq-bg);
            color: var(--hiq-text-main);
            transition: all 0.3s ease;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
        }

        main {
            margin-top: 80px;
            padding: 40px 20px 100px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn-link {
            color: var(--hiq-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid transparent;
        }

        .btn-link:hover {
            background-color: var(--hiq-light);
            border-color: var(--hiq-primary);
            transform: translateY(-2px);
        }

        /* Profile Layout Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 40px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Identity Card */
        .profile-card {
            background: var(--hiq-surface);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--hiq-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 100px;
        }

        .card-cover {
            height: 160px;
            background: linear-gradient(135deg, var(--hiq-primary) 0%, var(--hiq-accent) 100%);
            position: relative;
            overflow: hidden;
        }

        .card-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="2" fill="white" opacity="0.1"/></svg>');
            opacity: 0.2;
        }

        .avatar-container {
            position: relative;
            width: 140px;
            height: 140px;
            margin: -70px auto 20px;
            border-radius: 50%;
            background: var(--hiq-surface);
            padding: 6px;
            box-shadow: 0 15px 35px rgba(22, 163, 74, 0.2);
            border: 3px solid var(--hiq-border);
            z-index: 10;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            background: var(--hiq-gray-100);
        }

        .avatar-upload-badge {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 44px;
            height: 44px;
            background: var(--hiq-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid var(--hiq-surface);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.3);
            font-size: 1.1rem;
        }

        .avatar-upload-badge:hover {
            transform: scale(1.1);
            background: var(--hiq-secondary);
        }

        .profile-info {
            padding: 0 25px 30px;
            text-align: center;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .profile-email {
            font-size: 0.9rem;
            color: var(--hiq-text-soft);
            margin-bottom: 15px;
            word-break: break-all;
        }

        .verified-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--hiq-primary), var(--hiq-accent));
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .verified-badge.pending {
            background: var(--hiq-gray-100);
            color: var(--hiq-text-soft);
        }

        [data-theme="dark"] .verified-badge.pending {
            background: var(--hiq-gray-100);
            color: var(--hiq-text-main);
        }

        .profile-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            border-top: 1px solid var(--hiq-border);
            padding-top: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--hiq-primary);
            display: block;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--hiq-text-soft);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        /* Settings Sections */
        .settings-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .settings-section {
            background: var(--hiq-surface);
            border-radius: 20px;
            padding: 35px;
            border: 1px solid var(--hiq-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--hiq-primary);
            font-size: 1.4rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: var(--hiq-text-main);
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--hiq-border);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            color: var(--hiq-text-main);
            background: var(--hiq-bg);
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--hiq-primary);
            box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.1);
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Button Styles */
        .btn-save {
            background: linear-gradient(135deg, var(--hiq-primary), var(--hiq-accent));
            color: white;
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.2);
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(22, 163, 74, 0.3);
        }

        .btn-save:active {
            transform: translateY(-1px);
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .toast {
            background: var(--hiq-surface);
            border-radius: 12px;
            padding: 18px 24px;
            min-width: 320px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            animation: slideIn 0.4s ease;
            border-left: 5px solid var(--hiq-primary);
            border: 1px solid var(--hiq-border);
        }

        .toast.success {
            border-left-color: var(--hiq-primary);
        }

        .toast.error {
            border-left-color: #ef4444;
        }

        .toast-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .toast.success .toast-icon {
            background: rgba(22, 163, 74, 0.1);
            color: var(--hiq-primary);
        }

        .toast.error .toast-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .toast-content {
            flex: 1;
        }

        .toast-title {
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .toast-message {
            font-size: 0.85rem;
            color: var(--hiq-text-soft);
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* File Input */
        #profileImageInput {
            display: none;
        }

        /* Section Divider */
        .section-divider {
            height: 1px;
            background: var(--hiq-border);
            margin: 30px 0;
        }

        /* Info Box */
        .info-box {
            background: var(--hiq-light);
            border-left: 4px solid var(--hiq-primary);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 0.9rem;
            color: var(--hiq-text-soft);
        }

        [data-theme="dark"] .info-box {
            background: rgba(22, 163, 74, 0.1);
        }

        @media (max-width: 768px) {
            main {
                margin-top: 70px;
                padding: 20px 15px 80px;
            }

            .page-title {
                font-size: 1.8rem;
            }

            .profile-card {
                position: static;
            }

            .settings-section {
                padding: 25px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<main>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <div class="header-actions">
            <a href="dashboard.php" class="btn-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_profile" value="1">
        <input type="file" id="profileImageInput" name="profile_image" accept="image/jpeg,image/png,image/webp" onchange="previewImage(event)">

        <div class="profile-grid">
            <!-- Left: Profile Card -->
            <div>
                <div class="profile-card">
                    <div class="card-cover"></div>
                    
                    <div class="avatar-container">
                        <img src="<?php echo $profile_image; ?>" alt="Profile Avatar" class="avatar-img" id="avatarPreview">
                        <label for="profileImageInput" class="avatar-upload-badge" title="Change Profile Photo">
                            <i class="fa-solid fa-camera"></i>
                        </label>
                    </div>

                    <div class="profile-info">
                        <h2 class="profile-name"><?php echo $full_name; ?></h2>
                        <p class="profile-email"><?php echo $email; ?></p>
                        
                        <?php if ($is_verified === 1): ?>
                            <div class="verified-badge">
                                <i class="fa-solid fa-circle-check"></i> Verified Farmer
                            </div>
                        <?php else: ?>
                            <div class="verified-badge pending">
                                <i class="fa-regular fa-clock"></i> Verification Pending
                            </div>
                        <?php endif; ?>

                        <div class="profile-stats">
                            <div class="stat-item">
                                <span class="stat-value"><?php echo $role; ?></span>
                                <span class="stat-label">Role</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value"><?php echo $joined; ?></span>
                                <span class="stat-label">Joined</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Settings Sections -->
            <div class="settings-container">
                
                <!-- Basic Information -->
                <div class="settings-section">
                    <h3 class="section-title">
                        <i class="fa-solid fa-user-pen"></i> Basic Information
                    </h3>

                    <div class="info-box">
                        <i class="fa-solid fa-info-circle"></i> Update your profile information to help other farmers connect with you
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-input" value="<?php echo $full_name; ?>" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-input" value="<?php echo $email; ?>" disabled readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">About You (Bio)</label>
                        <textarea name="bio" class="form-textarea" placeholder="Tell other farmers about yourself, your experience, and what you grow..." maxlength="500"><?php echo $bio; ?></textarea>
                        <small style="color: var(--hiq-text-soft); margin-top: 5px; display: block;">Max 500 characters</small>
                    </div>
                </div>

                <!-- Farm Details -->
                <div class="settings-section">
                    <h3 class="section-title">
                        <i class="fa-solid fa-leaf"></i> Farm Details
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-input" placeholder="e.g., Kolkata, West Bengal, India" value="<?php echo $location; ?>" maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-input" placeholder="e.g., +91 98765 43210" value="<?php echo $phone; ?>" maxlength="20">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Farm Size (in acres)</label>
                            <input type="text" name="farm_size" class="form-input" placeholder="e.g., 2.5 acres" value="<?php echo $farm_size; ?>" maxlength="50">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Years of Experience</label>
                            <input type="text" name="experience" class="form-input" placeholder="e.g., 15 years" value="<?php echo $experience; ?>" maxlength="50">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Crops You Grow</label>
                        <textarea name="crops_grown" class="form-textarea" placeholder="e.g., Rice, Wheat, Vegetables, etc." maxlength="300"><?php echo $crops_grown; ?></textarea>
                        <small style="color: var(--hiq-text-soft); margin-top: 5px; display: block;">Max 300 characters</small>
                    </div>
                </div>

                <!-- Save Button -->
                <div style="text-align: right;">
                    <button type="submit" class="btn-save">
                        <i class="fa-solid fa-check"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</main>

<?php include 'footer.php'; ?>

<script>
    // Image preview
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('avatarPreview').src = reader.result;
        };
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Toast notification
    function showToast(type, title, message) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fa-solid ${icon}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideIn 0.4s ease reverse';
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // Show toast if there's a message
    <?php if (!empty($toast_msg)): ?>
        showToast('<?php echo $toast_type; ?>', '<?php echo $toast_type === 'success' ? 'Success' : 'Error'; ?>', '<?php echo addslashes($toast_msg); ?>');
    <?php endif; ?>

    // Theme toggle functionality
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('harvestiq-theme', newTheme);
    }
</script>

</body>
</html>
