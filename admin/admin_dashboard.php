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
$check_advisory = mysqli_query($conn, "SHOW TABLES LIKE 'weather_advisories'");
if (mysqli_num_rows($check_advisory) > 0) {
    $advisory_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS total FROM weather_advisories"))['total'] ?? 0;
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

<?php require 'admin_sidebar.php'; ?>

<div class="admin-main">
    <nav class="admin-top-nav">
        <div class="admin-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="userSearchInput" placeholder="Search farmers by name or email...">
        </div>
        <div class="nav-actions">
            <div class="dropdown">
                <button class="admin-profile-btn" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['full_name'] ?? 'Admin'); ?>&background=0F172A&color=fff&bold=true" alt="Admin">
                    <div class="admin-info d-none d-sm-block">
                        <h6><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Super Admin'); ?></h6>
                        <span>HarvestIQ Panel</span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg mt-2 border-0" style="border-radius:16px;">
                    <li><a class="dropdown-item fw-bold text-danger py-2" href="../logout.php"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout</a></li>
                </ul>
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
                    <div class="stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fa-solid fa-users"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($user_count); ?></h3><span>Total Users</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#ECFEFF;color:#06B6D4;"><i class="fa-solid fa-tractor"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($farmer_count); ?></h3><span>Registered Farmers</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#FFFBEB;color:#F59E0B;"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($market_count); ?></h3><span>Market Prices</span></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background:#EEF2FF;color:#4F46E5;"><i class="fa-solid fa-cloud-sun-rain"></i></div>
                    <div class="stat-info"><h3><?php echo number_format($advisory_count); ?></h3><span>Active Advisories</span></div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header-flex">
                <h5><i class="fa-solid fa-user-plus me-2" style="color:#16a34a;"></i> Recent Registrations</h5>
                <a href="manage_users.php" class="btn btn-light rounded-pill fw-bold small px-4 py-2 border-0" style="background:#f0fdf4;color:#16a34a;">Manage All</a>
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
                                <td class="text-muted user-email"><i class="fa-regular fa-envelope me-1 opacity-50"></i> <?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <span class="d-block fw-bold"><?php echo isset($row['created_at']) ? date('d M Y', strtotime($row['created_at'])) : 'Unknown'; ?></span>
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
