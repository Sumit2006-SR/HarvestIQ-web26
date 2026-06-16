<?php
// ডেটাবেস কানেকশন
require_once '../db.php';
$message = "";

// ==========================================
// 0. FETCH ADMIN DETAILS FROM USERS TABLE
// ==========================================
// এখানে আমরা users টেবিল থেকে সেই ইউজারকে খুঁজছি যার role হলো 'admin'
$admin_query = $conn->query("SELECT full_name, email FROM users WHERE role = 'admin' LIMIT 1");

if ($admin_query && $admin_query->num_rows > 0) {
  $admin_info = $admin_query->fetch_assoc();
  $admin_name = $admin_info['full_name'];
  $admin_email = $admin_info['email'];
} else {
  // যদি কোনো এডমিন না পাওয়া যায়, তবে ডিফল্ট হিসেবে এটি দেখাবে
  $admin_name = "System Admin";
  $admin_email = "admin@harvestiq.com";
}

// ==========================================
// 1. DELETE CROP LOGIC
// ==========================================
if (isset($_GET['delete_id'])) {
  $delete_id = $_GET['delete_id'];
  $del_stmt = $conn->prepare("DELETE FROM crop_knowledge WHERE id = ?");
  $del_stmt->bind_param("i", $delete_id);
  if ($del_stmt->execute()) {
    $message = "<div class='alert alert-danger alert-dismissible fade show'><i class='fas fa-trash-alt'></i> <strong>Deleted!</strong> Crop has been removed.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
  }
  $del_stmt->close();
}

// ==========================================
// 2. ADD NEW CROP LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_crop'])) {
  $name = $_POST['name'];
  $icon = $_POST['icon'];
  $soil_type = $_POST['soil_type'];
  $season = $_POST['season'];
  $water_req = $_POST['water_req'];
  $duration = $_POST['duration'];
  $yield_per_acre = $_POST['yield_per_acre'];
  $cost_per_acre = $_POST['cost_per_acre'];
  $price_per_kg = $_POST['price_per_kg'];
  $reason = $_POST['reason'];

  $stmt = $conn->prepare("INSERT INTO crop_knowledge (name, icon, soil_type, season, water_req, duration, yield_per_acre, cost_per_acre, price_per_kg, reason) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssssidds", $name, $icon, $soil_type, $season, $water_req, $duration, $yield_per_acre, $cost_per_acre, $price_per_kg, $reason);

  if ($stmt->execute()) {
    $message = "<div class='alert alert-success alert-dismissible fade show'><i class='fas fa-check-circle'></i> <strong>Success!</strong> New crop added.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
  }
  $stmt->close();
}

// ==========================================
// 3. EDIT CROP LOGIC
// ==========================================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_crop'])) {
  $id = $_POST['edit_id'];
  $name = $_POST['name'];
  $icon = $_POST['icon'];
  $soil_type = $_POST['soil_type'];
  $season = $_POST['season'];
  $water_req = $_POST['water_req'];
  $duration = $_POST['duration'];
  $yield_per_acre = $_POST['yield_per_acre'];
  $cost_per_acre = $_POST['cost_per_acre'];
  $price_per_kg = $_POST['price_per_kg'];
  $reason = $_POST['reason'];

  $update_stmt = $conn->prepare("UPDATE crop_knowledge SET name=?, icon=?, soil_type=?, season=?, water_req=?, duration=?, yield_per_acre=?, cost_per_acre=?, price_per_kg=?, reason=? WHERE id=?");
  $update_stmt->bind_param("ssssssiddsi", $name, $icon, $soil_type, $season, $water_req, $duration, $yield_per_acre, $cost_per_acre, $price_per_kg, $reason, $id);

  if ($update_stmt->execute()) {
    $message = "<div class='alert alert-info alert-dismissible fade show'><i class='fas fa-sync'></i> <strong>Updated!</strong> Crop details updated successfully.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
  }
  $update_stmt->close();
}

// Fetch all crops
$crops_result = $conn->query("SELECT * FROM crop_knowledge ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Crops | HarvestIQ Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f1f5f9;
      overflow-x: hidden;
    }

    

    /* Main Content Styles */
    .main-content {
      margin-left: 260px;
      padding: 30px;
      transition: 0.3s;
    }

    .top-navbar {
      background: white;
      padding: 15px 30px;
      border-radius: 15px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    /* User Profile Badge */
    .admin-badge {
      background: #f8fafc;
      padding: 8px 15px;
      border-radius: 50px;
      border: 1px solid #e2e8f0;
      transition: 0.3s;
    }

    .admin-badge:hover {
      background: #f1f5f9;
      cursor: pointer;
    }

    /* Card & Form Styles */
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
      border: 1px solid #e2e8f0;
      margin-bottom: 30px;
    }

    .card-header {
      background: white;
      border-bottom: 1px solid #f1f5f9;
      padding: 20px 25px;
      border-radius: 15px 15px 0 0 !important;
      font-weight: 700;
      color: #334155;
    }

    .form-control,
    .form-select {
      border-radius: 10px;
      padding: 10px 15px;
      border: 1px solid #cbd5e1;
      background-color: #f8fafc;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #10b981;
      box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
      background-color: white;
    }

    /* Buttons */
    .btn-success-custom {
      background: #10b981;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-weight: 600;
    }

    .btn-success-custom:hover {
      background: #059669;
      color: white;
    }

    /* Table Styles */
    .table-responsive {
      padding: 0 25px 25px 25px;
    }

    .table th {
      border-top: none;
      color: #64748b;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.85rem;
      padding-bottom: 15px;
    }

    .table td {
      vertical-align: middle;
      padding: 15px 10px;
      color: #334155;
      border-bottom: 1px solid #f1f5f9;
    }

    .action-btn {
      width: 35px;
      height: 35px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      transition: 0.2s;
      text-decoration: none;
    }

    .btn-edit {
      background: #eff6ff;
      color: #3b82f6;
    }

    .btn-edit:hover {
      background: #3b82f6;
      color: white;
    }

    .btn-delete {
      background: #fef2f2;
      color: #ef4444;
    }

    .btn-delete:hover {
      background: #ef4444;
      color: white;
    }
  </style>
</head>

<body>
<?php include "admin_sidebar.php" ?>

  <div class="main-content">

    <div class="top-navbar">
      <h4 class="mb-0 fw-bold text-dark">Crop Management</h4>

      <div class="user-profile d-flex align-items-center admin-badge">
        <div class="text-end me-3">
          <h6 class="mb-0 fw-bold text-dark">
            <?php echo htmlspecialchars($admin_name); ?>
            <span class="badge bg-success ms-1" style="font-size: 0.6rem; vertical-align: middle;">Admin</span>
          </h6>
          <small class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($admin_email); ?></small>
        </div>
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin_name); ?>&background=10b981&color=fff&bold=true&rounded=true" width="45" height="45" alt="Admin" class="shadow-sm">
      </div>
    </div>

    <?php echo $message; ?>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-plus-circle text-success me-2"></i> Add New Crop</span>
      </div>
      <div class="card-body p-4">
        <form method="POST">
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted">Crop Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Tomato" required>
            </div>
            <div class="col-md-1">
              <label class="form-label small fw-bold text-muted">Emoji</label>
              <input type="text" class="form-control text-center" name="icon" placeholder="🍅" required>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold text-muted">Soil Type</label>
              <select class="form-select" name="soil_type" required>
                <option value="Loamy">Loamy</option>
                <option value="Clay">Clay</option>
                <option value="Sandy">Sandy</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold text-muted">Season</label>
              <select class="form-select" name="season" required>
                <option value="Rabi">Rabi</option>
                <option value="Kharif">Kharif</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold text-muted">Water</label>
              <select class="form-select" name="water_req" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
              </select>
            </div>
            <div class="col-md-2">
              <label class="form-label small fw-bold text-muted">Duration</label>
              <input type="text" class="form-control" name="duration" placeholder="90 Days" required>
            </div>

            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted">Cost/Acre (₹)</label>
              <input type="number" class="form-control" name="cost_per_acre" required>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted">Yield/Acre (kg)</label>
              <input type="number" class="form-control" name="yield_per_acre" required>
            </div>
            <div class="col-md-3">
              <label class="form-label small fw-bold text-muted">Price/kg (₹)</label>
              <input type="number" step="0.01" class="form-control" name="price_per_kg" required>
            </div>
            <div class="col-md-12">
              <label class="form-label small fw-bold text-muted">AI Reason</label>
              <input type="text" class="form-control" name="reason" placeholder="Why is this good?" required>
            </div>
          </div>
          <div class="mt-4 text-end">
            <button type="submit" name="add_crop" class="btn btn-success-custom px-4"><i class="fas fa-save me-2"></i> Save Crop</button>
          </div>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <i class="fas fa-list text-primary me-2"></i> Database Records
      </div>
      <div class="card-body p-0 mt-3">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Crop Info</th>
                <th>Environment</th>
                <th>Financials (Per Acre)</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $crops_result->fetch_assoc()): ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="fs-2 me-3 bg-light rounded px-2"><?php echo $row['icon']; ?></div>
                      <div>
                        <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($row['name']); ?></h6>
                        <small class="text-muted"><i class="far fa-clock"></i> <?php echo $row['duration']; ?></small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2 py-1 mb-1"><?php echo $row['soil_type']; ?></span>
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-2 py-1"><?php echo $row['season']; ?></span>
                  </td>
                  <td>
                    <small class="d-block">Cost: <strong class="text-danger">₹<?php echo $row['cost_per_acre']; ?></strong></small>
                    <small class="d-block">Price: <strong class="text-success">₹<?php echo $row['price_per_kg']; ?></strong>/kg</small>
                  </td>
                  <td class="text-end">
                    <a href="#" class="action-btn btn-edit me-1" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this crop?');" title="Delete">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  </td>
                </tr>

                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Crop: <?php echo $row['name']; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <form method="POST">
                          <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                          <div class="row g-3">
                            <div class="col-md-6"><label class="small fw-bold text-muted">Crop Name</label><input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>" required></div>
                            <div class="col-md-2"><label class="small fw-bold text-muted">Emoji</label><input type="text" class="form-control text-center" name="icon" value="<?php echo $row['icon']; ?>" required></div>
                            <div class="col-md-4"><label class="small fw-bold text-muted">Duration</label><input type="text" class="form-control" name="duration" value="<?php echo $row['duration']; ?>" required></div>
                            <div class="col-md-4">
                              <label class="small fw-bold text-muted">Soil Type</label>
                              <select class="form-select" name="soil_type">
                                <option value="Loamy" <?php if ($row['soil_type'] == 'Loamy') echo 'selected'; ?>>Loamy</option>
                                <option value="Clay" <?php if ($row['soil_type'] == 'Clay') echo 'selected'; ?>>Clay</option>
                                <option value="Sandy" <?php if ($row['soil_type'] == 'Sandy') echo 'selected'; ?>>Sandy</option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <label class="small fw-bold text-muted">Season</label>
                              <select class="form-select" name="season">
                                <option value="Rabi" <?php if ($row['season'] == 'Rabi') echo 'selected'; ?>>Rabi</option>
                                <option value="Kharif" <?php if ($row['season'] == 'Kharif') echo 'selected'; ?>>Kharif</option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <label class="small fw-bold text-muted">Water</label>
                              <select class="form-select" name="water_req">
                                <option value="Low" <?php if ($row['water_req'] == 'Low') echo 'selected'; ?>>Low</option>
                                <option value="Medium" <?php if ($row['water_req'] == 'Medium') echo 'selected'; ?>>Medium</option>
                                <option value="High" <?php if ($row['water_req'] == 'High') echo 'selected'; ?>>High</option>
                              </select>
                            </div>
                            <div class="col-md-4"><label class="small fw-bold text-muted">Cost/Acre</label><input type="number" class="form-control" name="cost_per_acre" value="<?php echo $row['cost_per_acre']; ?>" required></div>
                            <div class="col-md-4"><label class="small fw-bold text-muted">Yield/Acre</label><input type="number" class="form-control" name="yield_per_acre" value="<?php echo $row['yield_per_acre']; ?>" required></div>
                            <div class="col-md-4"><label class="small fw-bold text-muted">Price/kg</label><input type="number" step="0.01" class="form-control" name="price_per_kg" value="<?php echo $row['price_per_kg']; ?>" required></div>
                            <div class="col-md-12"><label class="small fw-bold text-muted">AI Reason</label><textarea class="form-control" name="reason" rows="2" required><?php echo $row['reason']; ?></textarea></div>
                          </div>
                          <div class="mt-4 text-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_crop" class="btn btn-primary px-4">Update Details</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>