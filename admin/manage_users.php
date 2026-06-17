<?php
session_start();

if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    if ($delete_id == $_SESSION['user_id']) {
        echo "<script>alert('You cannot delete your own admin account!'); window.location.href='manage_users.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM users WHERE id = '$delete_id'");
        echo "<script>alert('User successfully deleted!'); window.location.href='manage_users.php';</script>";
    }
}

$all_users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$total_users = mysqli_num_rows($all_users);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Farmers | HarvestIQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    
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
        }

        [data-theme="dark"] {
            --bg-body: #020617;
            --bg-card: rgba(15, 23, 42, 0.6);
            --bg-input: rgba(30, 41, 59, 0.6);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
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

        .page-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: var(--text-main);
            margin: 0;
        }

        /* Toolbar Styling */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
            background: var(--bg-card);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
        }

        .search-wrapper {
            position: relative;
            flex-grow: 1;
            max-width: 400px;
        }

        .search-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-input {
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

        .search-input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        
        .search-input::placeholder { color: var(--text-muted); }

        .btn-add-user {
            background: linear-gradient(135deg, var(--green), var(--green-d));
            color: white;
            padding: 12px 24px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            border: none;
        }

        .btn-add-user:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* Glass Table Card */
        .custom-table-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
            overflow: hidden;
        }

        .table { margin: 0; color: var(--text-main); }
        
        .table thead th {
            background: rgba(16, 185, 129, 0.05);
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            padding: 18px 25px;
            border-bottom: 1.5px solid var(--border-color);
        }

        .table tbody td {
            padding: 18px 25px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-main);
            background: transparent;
        }

        .table tbody tr:hover td {
            background: rgba(16, 185, 129, 0.02);
        }

        /* User Profile Styling */
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .user-name {
            display: block;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--text-main);
        }

        .user-id {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-email { font-weight: 500; color: var(--text-muted); }

        /* Badges */
        .badge-admin {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid rgba(99, 102, 241, 0.2);
            display: inline-flex;
            align-items: center;
        }

        .badge-user {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-active {
            background: linear-gradient(135deg, var(--green), var(--green-d));
            color: white;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }

        /* Action Buttons */
        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            text-decoration: none;
            border: 1.5px solid;
            background: transparent;
        }

        .btn-delete {
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.1);
            transform: scale(1.1);
            color: #ef4444;
        }

        /* Member Count Badge */
        .member-count {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            padding: 8px 18px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.85rem;
            box-shadow: var(--glass-shadow);
        }

        @media (max-width: 991px) {
            .admin-main { margin-left: 0; padding: 20px; }
            .toolbar { flex-direction: column; align-items: stretch; }
            .search-wrapper { max-width: 100%; }
        }
        
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px) } to { opacity: 1; transform: none } }
        .animate-in { animation: fadeUp 0.6s ease forwards; }
    </style>
</head>
<body class="admin-body">

<?php require 'admin_sidebar.php'; ?>

<div class="admin-main animate-in">
    <div class="admin-page-wrap">
        
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <p class="mb-1" style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:1.5px; color: var(--green);">HarvestIQ Platform</p>
                <h2 class="page-title">Manage Farmers</h2>
            </div>
            <div class="member-count">
                <i class="fa-solid fa-users me-2 text-success"></i> <?php echo $total_users; ?> Registered Users
            </div>
        </div>

        <div class="toolbar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email or ID..." onkeyup="filterUsers()">
            </div>
            <a href="add_user.php" class="btn-add-user"><i class="fa-solid fa-user-plus"></i> Add New Farmer</a>
        </div>

        <div class="custom-table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Farmer Profile</th>
                            <th>Email Address</th>
                            <th>Access Level</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($all_users)) { ?>
                        <tr class="user-row">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['full_name']); ?>&background=random&color=fff&bold=true" class="user-avatar" alt="">
                                    <div>
                                        <span class="user-name search-name"><?php echo htmlspecialchars($row['full_name']); ?></span>
                                        <span class="user-id search-id">System ID: #<?php echo $row['id']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="search-email"><?php echo htmlspecialchars($row['email']); ?></span></td>
                            <td>
                                <?php if ($row['role'] == 'admin'): ?>
                                    <span class="badge-admin"><i class="fa-solid fa-crown me-2"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge-user"><i class="fa-solid fa-seedling me-2"></i> Farmer</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                    <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" title="Delete User" onclick="return confirm('Permanently delete this user from the system?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="badge-active"><i class="fa-solid fa-circle-check me-1"></i> Active Now</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script>
function filterUsers() {
    var input = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.user-row').forEach(function (row) {
        row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
    });
}
</script>
</body>
</html>