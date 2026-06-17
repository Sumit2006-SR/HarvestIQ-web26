<?php
// ডেটাবেস কানেকশন
require_once '../db.php';
$message = "";

// ==========================================
// 0. FETCH ADMIN DETAILS FROM USERS TABLE
// ==========================================
$admin_query = $conn->query("SELECT full_name, email FROM users WHERE role = 'admin' LIMIT 1");

if ($admin_query && $admin_query->num_rows > 0) {
  $admin_info = $admin_query->fetch_assoc();
  $admin_name = $admin_info['full_name'];
  $admin_email = $admin_info['email'];
} else {
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
    $message = "<div class='alert alert-danger custom-alert alert-dismissible fade show'><i class='fa-solid fa-trash-can me-2'></i> <strong>Deleted!</strong> Crop has been permanently removed.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
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
    $message = "<div class='alert alert-success custom-alert alert-dismissible fade show'><i class='fa-solid fa-circle-check me-2'></i> <strong>Success!</strong> New crop profile added to AI Engine.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
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
    $message = "<div class='alert alert-info custom-alert alert-dismissible fade show'><i class='fa-solid fa-arrows-rotate me-2'></i> <strong>Updated!</strong> Crop parameters successfully synchronized.<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Favicon for Browser Tab -->
<!-- Favicon for Admin Pages -->
<link rel="icon" type="image/png" href="../assets/logo-192.png">  <style>
    :root {
      --green: #10b981;
      --green-d: #059669;
      --navy: #0f172a;
      --slate: #475569;
      --glass: rgba(255, 255, 255, 0.75);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #d4f5e9 0%, #bfdbfe 50%, #e0e7ff 100%);
      background-size: 300% 300%;
      animation: bgShift 20s ease infinite;
      min-height: 100vh;
      color: var(--navy);
      overflow-x: hidden;
    }

    @keyframes bgShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* ── LAYOUT & NAVBAR ── */
    .main-content {
      margin-left: 280px;
      min-height: 100vh;
      padding: 32px 36px;
    }

    .top-navbar {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(20px);
      border: 1.5px solid rgba(255, 255, 255, 0.9);
      padding: 16px 24px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .page-title {
      font-family: 'Poppins', sans-serif;
      font-size: 1.4rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      color: var(--navy);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* ── ADMIN BADGE FIX ── */
    .admin-badge {
      background: #f8fafc;
      padding: 8px 16px;
      border-radius: 50px;
      border: 1.5px solid #e2e8f0;
      display: inline-flex !important;
      flex-direction: row !important;
      align-items: center !important;
      gap: 12px;
      transition: all 0.3s ease;
      white-space: nowrap !important; /* লেখাকে এক লাইনে রাখতে বাধ্য করবে */
      width: max-content;
    }
    .admin-badge:hover { background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

    /* ── BUTTON TEXT FIX ── */
    .btn-action {
      background: linear-gradient(135deg, var(--green), var(--green-d));
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 13px 24px;
      font-weight: 700;
      font-size: 0.95rem;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 9px;
      box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
      white-space: nowrap !important; /* বাটনের লেখা ভেঙে নিচে নামবে না */
      min-width: max-content;
    }
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(16, 185, 129, 0.45);
      color: #fff;
    }

    /* ── ALERTS ── */
    .custom-alert {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      font-family: 'Poppins', sans-serif;
      font-size: 0.95rem;
    }
    .custom-alert.alert-success { border-left: 5px solid #10b981; color: #047857; }
    .custom-alert.alert-danger { border-left: 5px solid #ef4444; color: #b91c1c; }
    .custom-alert.alert-info { border-left: 5px solid #3b82f6; color: #1d4ed8; }

    /* ── GLASS CARDS ── */
    .g-card {
      background: var(--glass);
      backdrop-filter: blur(28px);
      -webkit-backdrop-filter: blur(28px);
      border: 1.5px solid rgba(255, 255, 255, 0.8);
      border-radius: 24px;
      box-shadow: 0 16px 45px rgba(0, 0, 0, 0.07);
      margin-bottom: 30px;
      overflow: hidden;
    }

    .g-card-header {
      background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.6));
      border-bottom: 1px solid rgba(203, 213, 225, 0.5);
      padding: 20px 26px;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      color: var(--navy);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .g-card-body { padding: 26px; }

    /* ── FORM ELEMENTS ── */
    .f-label {
      font-weight: 700;
      font-size: 0.82rem;
      color: var(--slate);
      text-transform: uppercase;
      letter-spacing: 0.8px;
      margin-bottom: 8px;
    }

    .f-input {
      background: rgba(255, 255, 255, 0.9);
      border: 1.5px solid rgba(203, 213, 225, 0.6);
      border-radius: 14px;
      padding: 12px 16px;
      font-family: 'Outfit', sans-serif;
      font-size: 0.95rem;
      color: var(--navy);
      transition: all 0.25s ease;
      width: 100%;
    }
    .f-input:focus {
      outline: none;
      border-color: var(--green);
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
      background: #fff;
    }

    .btn-action {
      background: linear-gradient(135deg, var(--green), var(--green-d));
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 13px 24px;
      font-weight: 700;
      font-size: 0.95rem;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 9px;
      box-shadow: 0 8px 24px rgba(16, 185, 129, 0.35);
    }
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(16, 185, 129, 0.45);
      color: #fff;
    }

    /* ── TABLE STYLES ── */
    .table-scroll { overflow-x: auto; padding: 0 26px 26px; }
    .table-scroll::-webkit-scrollbar { height: 6px; }
    .table-scroll::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.4); border-radius: 10px; }

    .c-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
    .c-table thead th {
      background: transparent;
      padding: 10px 18px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--slate);
      border: none;
      white-space: nowrap;
    }
    .c-table tbody tr {
      background: rgba(255, 255, 255, 0.6);
      transition: all 0.3s ease;
    }
    .c-table tbody tr td:first-child { border-radius: 16px 0 0 16px; }
    .c-table tbody tr td:last-child { border-radius: 0 16px 16px 0; }
    
    .c-table tbody tr:hover {
      background: #fff;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      transform: translateY(-2px);
    }

    .c-table td {
      padding: 16px 18px;
      vertical-align: middle;
      border: none;
      border-top: 1px solid rgba(255,255,255,0.5);
      border-bottom: 1px solid rgba(255,255,255,0.5);
    }

    .crop-icon-box {
      width: 48px; height: 48px;
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      border: 1px solid rgba(203, 213, 225, 0.5);
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem; flex-shrink: 0;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* ── BADGES ── */
    .c-badge {
      display: inline-flex; align-items: center; gap: 4px;
      padding: 4px 10px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; border: 1px solid;
      margin: 2px;
    }
    .b-loamy { background: rgba(16, 185, 129, 0.1); color: #047857; border-color: rgba(16, 185, 129, 0.2); }
    .b-clay { background: rgba(245, 158, 11, 0.1); color: #b45309; border-color: rgba(245, 158, 11, 0.2); }
    .b-sandy { background: rgba(59, 130, 246, 0.1); color: #1d4ed8; border-color: rgba(59, 130, 246, 0.2); }
    .b-rabi { background: rgba(99, 102, 241, 0.1); color: #4338ca; border-color: rgba(99, 102, 241, 0.2); }
    .b-kharif { background: rgba(236, 72, 153, 0.1); color: #be185d; border-color: rgba(236, 72, 153, 0.2); }
    .b-water { background: rgba(14, 165, 233, 0.1); color: #0369a1; border-color: rgba(14, 165, 233, 0.2); }

    /* ── ACTION BUTTONS ── */
    .icon-btn {
      width: 36px; height: 36px; border-radius: 10px; border: 1.5px solid;
      display: inline-flex; align-items: center; justify-content: center;
      font-size: 0.9rem; transition: all 0.2s ease; background: #fff; cursor: pointer; text-decoration: none;
    }
    .btn-edit-sm { border-color: rgba(59, 130, 246, 0.3); color: #3b82f6; }
    .btn-edit-sm:hover { background: rgba(59, 130, 246, 0.1); transform: scale(1.1); color: #3b82f6;}
    
    .btn-del-sm { border-color: rgba(239, 68, 68, 0.3); color: #ef4444; }
    .btn-del-sm:hover { background: rgba(239, 68, 68, 0.1); transform: scale(1.1); color: #ef4444;}

    /* ── MODAL (GLASS) ── */
    .modal-content {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255,255,255,0.8);
      border-radius: 24px;
      box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    }
    .modal-header { border-bottom: 1px solid rgba(203, 213, 225, 0.5); padding: 20px 26px; }
    .modal-body { padding: 26px; }

    @keyframes fadeUp { from { opacity: 0; transform: translateY(20px) } to { opacity: 1; transform: none } }
    .animate-in { animation: fadeUp 0.6s ease forwards; }

    @media (max-width: 991px) {
      .main-content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>

<body>
  <?php include "admin_sidebar.php" ?>

  <div class="main-content animate-in">

    <div class="top-navbar">
      <h4 class="page-title"><i class="fa-solid fa-leaf text-success"></i> Crop Matrix Database</h4>
      <div class="admin-badge">
        <div class="text-end">
          <div class="fw-bold" style="font-size: 0.9rem; color: var(--navy); line-height: 1;">
            <?php echo htmlspecialchars($admin_name); ?>
          </div>
          <span class="text-muted" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase;">System Admin</span>
        </div>
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($admin_name); ?>&background=10b981&color=fff&bold=true&rounded=12" width="40" height="40" alt="Admin">
      </div>
    </div>

    <?php echo $message; ?>

    <div class="g-card">
      <div class="g-card-header">
        <i class="fa-solid fa-microchip text-primary fs-5"></i> Add New AI Crop Rule
      </div>
      <div class="g-card-body">
        <form method="POST">
          <div class="row g-3">
            <div class="col-lg-3 col-md-6">
              <label class="f-label">Crop Name</label>
              <input type="text" class="f-input" name="name" placeholder="e.g. Basmati Rice" required>
            </div>
            <div class="col-lg-1 col-md-2 col-4">
    <label class="f-label">Icon</label>
    <select class="f-input" name="icon" required style="font-family: 'Segoe UI Emoji', sans-serif;">
        <option value="🌾">🌾 Rice</option>
        <option value="🌽">🌽 Maize</option>
        <option value="🥔">🥔 Potato</option>
        <option value="🍅">🍅 Tomato</option>
        <option value="🧅">🧅 Onion</option>
        <option value="🌶️">🌶️ Chilli</option>
        <option value="🥬">🥬 Spinach</option>
        <option value="🍆">🍆 Eggplant</option>
        <option value="🥕">🥕 Carrot</option>
        <option value="🥦">🥦 Broccoli</option>
        <option value="🌻">🌻 Sunflower</option>
        <option value="🥜">🥜 Groundnut</option>
    </select>
</div>
            <div class="col-lg-2 col-md-4 col-8">
              <label class="f-label">Soil Req.</label>
              <select class="f-input" name="soil_type" required>
                <option value="Loamy">Loamy</option>
                <option value="Clay">Clay</option>
                <option value="Sandy">Sandy</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label class="f-label">Season</label>
              <select class="f-input" name="season" required>
                <option value="Rabi">Rabi (Winter)</option>
                <option value="Kharif">Kharif (Monsoon)</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label class="f-label">Water Usage</label>
              <select class="f-input" name="water_req" required>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
              </select>
            </div>
            <div class="col-lg-2 col-md-4">
              <label class="f-label">Duration</label>
              <input type="text" class="f-input" name="duration" placeholder="e.g. 120 Days" required>
            </div>

            <div class="col-lg-3 col-md-4">
              <label class="f-label">Cost/Acre (₹)</label>
              <input type="number" class="f-input fw-bold text-danger" name="cost_per_acre" placeholder="e.g. 15000" required>
            </div>
            <div class="col-lg-3 col-md-4">
              <label class="f-label">Yield/Acre (kg)</label>
              <input type="number" class="f-input" name="yield_per_acre" placeholder="e.g. 2000" required>
            </div>
            <div class="col-lg-3 col-md-4">
              <label class="f-label">Market Price/kg (₹)</label>
              <input type="number" step="0.01" class="f-input fw-bold text-success" name="price_per_kg" placeholder="e.g. 25.50" required>
            </div>
           <div class="col-lg-12">
    <label class="f-label">Why is this crop recommended? (Expert Advice)</label>
    <textarea class="f-input" name="reason" rows="3" placeholder="Example: Best for monsoon season as it requires less water and grows well in sandy soil..." required></textarea>
    <small class="text-muted" style="color: #64748b; font-size: 0.8rem;">* This advice will be shown to farmers when they check crop recommendations.</small>
</div>
          </div>
          <div class="mt-4 d-flex justify-content-end">
    <button type="submit" name="add_crop" class="btn-action" style="background: #16a34a; color: white;">
        <i class="fa-solid fa-floppy-disk"></i> Save Farming Rule
    </button>
</div>
        </form>
      </div>
    </div>

    <div class="g-card p-0 pb-3">
      <div class="g-card-header border-0 mb-2">
        <i class="fa-solid fa-server text-indigo-500" style="color:#6366f1;"></i> Active Crop Knowledge Base
      </div>
      <div class="table-scroll">
        <table class="c-table">
          <thead>
            <tr>
              <th style="padding-left: 25px;">Crop Profile</th>
              <th>Environment Parameters</th>
              <th>Financial Projections (Per Acre)</th>
              <th class="text-end" style="padding-right: 25px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $crops_result->fetch_assoc()): ?>
              <?php 
                // Determine Badge Colors
                $soil_class = ($row['soil_type'] == 'Loamy') ? 'b-loamy' : (($row['soil_type'] == 'Clay') ? 'b-clay' : 'b-sandy');
                $season_class = ($row['season'] == 'Rabi') ? 'b-rabi' : 'b-kharif';
              ?>
              <tr>
                <td style="padding-left: 25px;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="crop-icon-box"><?php echo $row['icon']; ?></div>
                    <div>
                      <div class="fw-bold" style="font-family:'Plus Jakarta Sans'; font-size:1.05rem;"><?php echo htmlspecialchars($row['name']); ?></div>
                      <div class="text-muted" style="font-size:0.8rem; font-weight:600;"><i class="far fa-clock"></i> <?php echo htmlspecialchars($row['duration']); ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="c-badge <?php echo $soil_class; ?>"><i class="fa-solid fa-layer-group"></i> <?php echo $row['soil_type']; ?></span>
                  <span class="c-badge <?php echo $season_class; ?>"><i class="fa-solid fa-cloud-sun"></i> <?php echo $row['season']; ?></span>
                  <span class="c-badge b-water"><i class="fa-solid fa-droplet"></i> <?php echo $row['water_req']; ?> Water</span>
                </td>
                <td>
                  <div style="font-family:'Outfit'; font-size:0.9rem;">
                    <span class="text-muted fw-bold">Est. Cost:</span> <span class="text-danger fw-bold">₹<?php echo number_format($row['cost_per_acre']); ?></span><br>
                    <span class="text-muted fw-bold">Selling Price:</span> <span class="text-success fw-bold">₹<?php echo number_format($row['price_per_kg'], 2); ?></span> <small class="text-muted">/ kg</small>
                  </div>
                </td>
                <td class="text-end" style="padding-right: 25px;">
                  <div class="d-flex gap-2 justify-content-end">
                    <a href="#" class="icon-btn btn-edit-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>" title="Edit">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="icon-btn btn-del-sm" onclick="return confirm('Are you sure you want to permanently delete this crop rule?');" title="Delete">
                      <i class="fa-solid fa-trash-can"></i>
                    </a>
                  </div>
                </td>
              </tr>

              <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title fw-bold" style="font-family:'Plus Jakarta Sans';"><i class="fa-solid fa-pen-nib text-primary me-2"></i> Edit Knowledge Base: <?php echo $row['name']; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <form method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                        <div class="row g-3">
                          <div class="col-md-6"><label class="f-label">Crop Name</label><input type="text" class="f-input" name="name" value="<?php echo $row['name']; ?>" required></div>
                          <div class="col-md-2"><label class="f-label">Icon</label><input type="text" class="f-input text-center fs-4 p-1" name="icon" value="<?php echo $row['icon']; ?>" required></div>
                          <div class="col-md-4"><label class="f-label">Duration</label><input type="text" class="f-input" name="duration" value="<?php echo $row['duration']; ?>" required></div>
                          <div class="col-md-4">
                            <label class="f-label">Soil Type</label>
                            <select class="f-input" name="soil_type">
                              <option value="Loamy" <?php if ($row['soil_type'] == 'Loamy') echo 'selected'; ?>>Loamy</option>
                              <option value="Clay" <?php if ($row['soil_type'] == 'Clay') echo 'selected'; ?>>Clay</option>
                              <option value="Sandy" <?php if ($row['soil_type'] == 'Sandy') echo 'selected'; ?>>Sandy</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label class="f-label">Season</label>
                            <select class="f-input" name="season">
                              <option value="Rabi" <?php if ($row['season'] == 'Rabi') echo 'selected'; ?>>Rabi</option>
                              <option value="Kharif" <?php if ($row['season'] == 'Kharif') echo 'selected'; ?>>Kharif</option>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <label class="f-label">Water Usage</label>
                            <select class="f-input" name="water_req">
                              <option value="Low" <?php if ($row['water_req'] == 'Low') echo 'selected'; ?>>Low</option>
                              <option value="Medium" <?php if ($row['water_req'] == 'Medium') echo 'selected'; ?>>Medium</option>
                              <option value="High" <?php if ($row['water_req'] == 'High') echo 'selected'; ?>>High</option>
                            </select>
                          </div>
                          <div class="col-md-4"><label class="f-label">Cost/Acre (₹)</label><input type="number" class="f-input fw-bold text-danger" name="cost_per_acre" value="<?php echo $row['cost_per_acre']; ?>" required></div>
                          <div class="col-md-4"><label class="f-label">Yield/Acre (kg)</label><input type="number" class="f-input" name="yield_per_acre" value="<?php echo $row['yield_per_acre']; ?>" required></div>
                          <div class="col-md-4"><label class="f-label">Price/kg (₹)</label><input type="number" step="0.01" class="f-input fw-bold text-success" name="price_per_kg" value="<?php echo $row['price_per_kg']; ?>" required></div>
                          <div class="col-md-12"><label class="f-label">AI Agronomic Reason</label><textarea class="f-input" name="reason" rows="2" required><?php echo $row['reason']; ?></textarea></div>
                        </div>
                        <div class="mt-4 text-end">
                          <button type="button" class="btn btn-light rounded-pill px-4 fw-bold me-2" data-bs-dismiss="modal">Cancel</button>
                          <button type="submit" name="edit_crop" class="btn-action"><i class="fa-solid fa-arrows-rotate"></i> Update Engine</button>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>