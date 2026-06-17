<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS total FROM users"))['total'] ?? 0;
$farmer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS total FROM users WHERE role='farmer'"))['total'] ?? 0;

$market_count = 0;
$check_market = mysqli_query($conn, "SHOW TABLES LIKE 'market_prices'");
if (mysqli_num_rows($check_market) > 0) {
    $market_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS total FROM market_prices"))['total'] ?? 0;
}

$advisory_count = 0;
$check_advisory = mysqli_query($conn, "SHOW TABLES LIKE 'weather_alerts'"); // Changed to weather_alerts as per previous code
if (mysqli_num_rows($check_advisory) > 0) {
    $advisory_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS total FROM weather_alerts WHERE is_active=1"))['total'] ?? 0;
}

$recent_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Overview | HarvestIQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    <!-- Favicon for Admin Pages -->
<link rel="icon" type="image/png" href="../assets/logo-192.png">
    <style>
        /* ==========================================
           PREMIUM THEME VARIABLES (Dark & Light)
        ========================================== */
        :root {
            --green: #10b981;
            --green-d: #059669;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --bg-input: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --glass-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --nav-bg: rgba(255, 255, 255, 0.85);
        }

        [data-theme="dark"] {
            --bg-body: #020617;
            --bg-card: rgba(15, 23, 42, 0.6);
            --bg-input: rgba(30, 41, 59, 0.6);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            --nav-bg: rgba(15, 23, 42, 0.75);
        }

        body.admin-body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            overflow-x: hidden;
            min-height: 100vh;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        .admin-main {
            margin-left: 280px;
            padding: 30px;
            transition: 0.3s;
        }

        /* --- Top Navigation --- */
        .admin-top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 15px 25px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--glass-shadow);
            margin-bottom: 30px;
        }

        .admin-search {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .admin-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .admin-search input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1.5px solid var(--border-color);
            border-radius: 14px;
            background: var(--bg-input);
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .admin-search input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        /* --- Profile Dropdown --- */
        .admin-profile-btn {
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            text-align: left;
        }
        .admin-profile-btn img {
            width: 45px; height: 45px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .admin-info h6 { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--text-main); font-size: 0.95rem; }
        .admin-info span { font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

        .header-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main);
        }

        /* --- Stat Cards --- */
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
        }
        .stat-card:hover { transform: translateY(-5px); border-color: rgba(16, 185, 129, 0.3); }

        .stat-icon {
            width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;
        }
        .stat-info h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 900; margin: 0; color: var(--text-main); line-height: 1;}
        .stat-info span { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        /* --- Glass Table Card --- */
        .content-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
            overflow: hidden;
            margin-top: 10px;
        }

        .card-header-flex {
            padding: 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(16, 185, 129, 0.02);
        }
        .card-header-flex h5 { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--text-main); }

        .table-custom { margin: 0; color: var(--text-main); }
        .table-custom thead th {
            background: rgba(16, 185, 129, 0.05); color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; padding: 18px 25px; border-bottom: 1.5px solid var(--border-color);
        }
        .table-custom tbody td {
            padding: 18px 25px; vertical-align: middle; border-bottom: 1px solid var(--border-color); color: var(--text-main); background: transparent;
        }
        .table-custom tbody tr:hover td { background: rgba(16, 185, 129, 0.03); }

        /* User Profile in Table */
        .user-pill { display: flex; align-items: center; gap: 12px; }
        .avatar-small { width: 40px; height: 40px; border-radius: 10px; }
        .user-name { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1rem; color: var(--text-main); }
        .user-email { color: var(--text-muted); font-size: 0.9rem; }

        /* Badges */
        .badge-admin { background: rgba(99, 102, 241, 0.1); color: #6366f1; padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(99, 102, 241, 0.2); }
        .badge-user { background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.2); }

        /* Custom Btn */
        .btn-custom-outline {
            background: rgba(16, 185, 129, 0.1); color: var(--green); border: 1px solid rgba(16, 185, 129, 0.3); padding: 8px 20px; border-radius: 50px; font-weight: 700; font-size: 0.85rem; text-decoration: none; transition: 0.3s;
        }
        .btn-custom-outline:hover { background: var(--green); color: #fff; }

        @media (max-width: 991px) {
            .admin-main { margin-left: 0; padding: 20px; }
            .admin-top-nav { flex-direction: column; gap: 15px; align-items: stretch; }
            .admin-search { max-width: 100%; }
        }
        
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px) } to { opacity: 1; transform: none } }
        .animate-in { animation: fadeUp 0.6s ease forwards; }
    </style>
</head>
<body class="admin-body">

<?php require 'admin_sidebar.php'; ?>

<div class="admin-main animate-in">
    <nav class="admin-top-nav">
        <div class="admin-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="userSearchInput" placeholder="Search dashboard...">
        </div>
        <div class="nav-actions">
            <div class="dropdown">
                <button class="admin-profile-btn" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name'] ?? 'Admin'); ?>&background=10b981&color=fff&bold=true" alt="Admin">
                    <div class="admin-info d-none d-sm-block">
                        <h6><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Super Admin'); ?></h6>
                        <span>HarvestIQ Panel</span>
                    </div>
                </button>
            </div>
        </div>
    </nav>

    <div class="dashboard-container">
        <div class="mb-4">
            <h2 class="header-title">Welcome back, <?php echo explode(' ', $_SESSION['full_name'] ?? 'Admin')[0]; ?>!</h2>
            <p class="text-muted fw-medium m-0">Here's your agriculture platform overview today.</p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($user_count); ?></h3><span>Total Users</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4;"><i class="fa-solid fa-tractor"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($farmer_count); ?></h3><span>Registered Farmers</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($market_count); ?></h3><span>Market Prices</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fa-solid fa-tower-broadcast"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($advisory_count); ?></h3><span>Active Alerts</span></div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header-flex">
                <h5><i class="fa-solid fa-user-plus me-2 text-success"></i> Recent Registrations</h5>
                <a href="manage_users.php" class="btn-custom-outline">Manage All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0" id="userTable">
                    <thead>
                        <tr>
                            <th>Farmer Profile</th>
                            <th>Email Address</th>
                            <th>Join Date & Time</th>
                            <th class="text-end">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($recent_users) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($recent_users)): ?>
                            <tr class="user-row">
                                <td>
                                    <div class="user-pill">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['full_name']); ?>&background=random&color=fff&bold=true" class="avatar-small" alt="">
                                        <span class="user-name"><?php echo htmlspecialchars($row['full_name']); ?></span>
                                    </div>
                                </td>
                                <td class="user-email"><i class="fa-regular fa-envelope me-2 opacity-50"></i><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <span class="d-block fw-bold" style="color: var(--text-main);"><?php echo isset($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : 'Unknown'; ?></span>
                                    <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i> <?php echo isset($row['created_at']) ? date('h:i A', strtotime($row['created_at'])) : '--:--'; ?></span>
                                </td>
                                <td class="text-end">
                                    <?php if ($row['role'] == 'admin'): ?>
                                        <span class="badge-admin">Admin</span>
                                    <?php else: ?>
                                        <span class="badge-user">Farmer</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('userSearchInput').addEventListener('keyup', function () {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.user-row').forEach(function (row) {
        var name = row.querySelector('.user-name').innerText.toLowerCase();
        var email = row.querySelector('.user-email').innerText.toLowerCase();
        row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
    });
});
</script>
</body>
</html>