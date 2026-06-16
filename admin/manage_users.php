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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

<?php require 'admin_sidebar.php'; ?>

<div class="admin-main">
    <div class="admin-page-wrap">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <p class="text-muted mb-1 fw-semibold" style="font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;">HarvestIQ Platform</p>
                <h2 class="page-title">Manage Farmers</h2>
            </div>
            <div class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold shadow-sm">
                <i class="fa-solid fa-users me-2" style="color:#16a34a;"></i> <?php echo $total_users; ?> Total Members
            </div>
        </div>

        <div class="toolbar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email or ID..." onkeyup="filterUsers()">
            </div>
            <a href="add_user.php" class="btn-add-user"><i class="fa-solid fa-user-plus"></i> Add New Farmer</a>
        </div>

        <div class="custom-table-card p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Farmer Profile</th>
                            <th>Email Address</th>
                            <th>Role</th>
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
                                        <span class="user-id search-id">ID: #<?php echo $row['id']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="search-email"><?php echo htmlspecialchars($row['email']); ?></span></td>
                            <td>
                                <?php if ($row['role'] == 'admin'): ?>
                                    <span class="badge-admin"><i class="fa-solid fa-crown me-1"></i> Admin</span>
                                <?php else: ?>
                                    <span class="badge-user">Farmer</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                    <a href="manage_users.php?delete=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete this user?');">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-success">Active Now</span>
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
