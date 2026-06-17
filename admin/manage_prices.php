<?php
session_start();

// Admin security check
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require '../db.php';

$alert_message = '';

// --- 1. ADD NEW ENTRY LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_price'])) {
    $crop_name = mysqli_real_escape_string($conn, trim($_POST['crop_name']));
    $icon = mysqli_real_escape_string($conn, trim($_POST['icon']));
    $price = mysqli_real_escape_string($conn, trim($_POST['price']));
    $unit = mysqli_real_escape_string($conn, trim($_POST['unit']));
    $mandi_name = mysqli_real_escape_string($conn, trim($_POST['mandi_name']));
    $trend = mysqli_real_escape_string($conn, $_POST['trend']);
    $price_change = mysqli_real_escape_string($conn, trim($_POST['price_change']));

    $insert_query = "INSERT INTO market_prices (crop_name, icon, price, unit, mandi_name, trend, price_change) 
                     VALUES ('$crop_name', '$icon', '$price', '$unit', '$mandi_name', '$trend', '$price_change')";
    
    if (mysqli_query($conn, $insert_query)) {
        $alert_message = '<div class="alert custom-alert alert-success shadow-sm rounded-4 alert-dismissible fade show"><i class="fa-solid fa-circle-check me-2"></i> <strong>Success!</strong> Market asset successfully listed!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    } else {
        $alert_message = '<div class="alert custom-alert alert-danger shadow-sm rounded-4 alert-dismissible fade show"><i class="fa-solid fa-circle-exclamation me-2"></i> <strong>Error!</strong> Failed to add asset.<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

// --- 2. EDIT/UPDATE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_price'])) {
    $edit_id = mysqli_real_escape_string($conn, $_POST['edit_id']);
    $price = mysqli_real_escape_string($conn, trim($_POST['price']));
    $trend = mysqli_real_escape_string($conn, $_POST['trend']);
    $price_change = mysqli_real_escape_string($conn, trim($_POST['price_change']));

    $update_query = "UPDATE market_prices SET price='$price', trend='$trend', price_change='$price_change' WHERE id='$edit_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $alert_message = '<div class="alert custom-alert alert-info shadow-sm rounded-4 alert-dismissible fade show"><i class="fa-solid fa-arrows-rotate me-2"></i> <strong>Updated!</strong> Market data updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }
}

// --- 3. DELETE LOGIC ---
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM market_prices WHERE id = '$delete_id'");
    echo "<script>window.location.href='manage_prices.php';</script>";
}

// Fetch all data & Calculate Stats
$all_prices = mysqli_query($conn, "SELECT * FROM market_prices ORDER BY updated_at DESC");
$total_crops = mysqli_num_rows($all_prices);

$profit_markets = 0;
$loss_markets = 0;
$stats_query = mysqli_query($conn, "SELECT trend, COUNT(*) as count FROM market_prices GROUP BY trend");
while($stat = mysqli_fetch_assoc($stats_query)) {
    if($stat['trend'] == 'up') $profit_markets = $stat['count'];
    if($stat['trend'] == 'down') $loss_markets = $stat['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Market Data Hub | HarvestIQ Admin</title>
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
            --modal-bg: rgba(255, 255, 255, 0.95);
        }

        [data-theme="dark"] {
            --bg-body: #020617;
            --bg-card: rgba(15, 23, 42, 0.6);
            --bg-input: rgba(30, 41, 59, 0.6);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.08);
            --glass-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            --modal-bg: rgba(15, 23, 42, 0.95);
        }

.text-muted {
        color: var(--text-muted) !important;
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
        
        .brand-font { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* --- Custom Alerts --- */
        .custom-alert { backdrop-filter: blur(10px); border: none; }
        .custom-alert.alert-success { background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; color: #10b981; }
        .custom-alert.alert-danger { background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; color: #ef4444; }
        .custom-alert.alert-info { background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; color: #3b82f6; }

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
        .stat-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0; }
        .stat-card h3 { color: var(--text-main); }

        /* --- Admin Panel Cards --- */
        .admin-panel-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
            padding: 30px;
            margin-bottom: 30px;
        }
        .admin-panel-card.p-0 { padding: 0; }

        /* --- Form Elements --- */
        .form-control-custom, .form-select-custom {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid var(--border-color);
            border-radius: 14px;
            background: var(--bg-input);
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
            background: var(--bg-card);
        }
        .form-control-custom::placeholder { color: var(--text-muted); }
        [data-theme="dark"] .form-select-custom option { background: #0f172a; color: #fff; }
        [data-theme="dark"] .form-select-custom optgroup { background: #1e293b; color: #94a3b8; }

        /* --- Buttons --- */
        .btn-brand {
            background: linear-gradient(135deg, var(--green), var(--green-d));
            color: white;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4); color: white; }

        /* --- Table Styling --- */
        .table-custom { margin: 0; color: var(--text-main); }
        .table-custom thead th {
            background: rgba(16, 185, 129, 0.05); color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; padding: 18px 25px; border-bottom: 1.5px solid var(--border-color);
        }
        .table-custom tbody td {
            padding: 18px 25px; vertical-align: middle; border-bottom: 1px solid var(--border-color); color: var(--text-main); background: transparent;
        }
        .table-custom tbody tr:hover td { background: rgba(16, 185, 129, 0.03); }

        .icon-box {
            width: 45px; height: 45px; background: rgba(16, 185, 129, 0.1); border-radius: 12px;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }

        /* --- Trend Badges --- */
        .badge-trend { padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; border: 1px solid; display: inline-flex; align-items: center; gap: 6px; }
        .badge-up { background: rgba(16, 185, 129, 0.1); color: #10b981; border-color: rgba(16, 185, 129, 0.2); }
        .badge-down { background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2); }
        .badge-stable { background: rgba(100, 116, 139, 0.1); color: #64748b; border-color: rgba(100, 116, 139, 0.2); }

        .profit-text { color: #10b981; font-weight: 800; font-family: 'Plus Jakarta Sans';}
        .loss-text { color: #ef4444; font-weight: 800; font-family: 'Plus Jakarta Sans';}
        .stable-text { color: var(--text-main); font-weight: 800; font-family: 'Plus Jakarta Sans';}

        /* --- Action Buttons --- */
        .action-btn { width: 38px; height: 38px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; text-decoration: none; border: 1.5px solid; background: transparent; }
        .btn-edit { border-color: rgba(59, 130, 246, 0.3); color: #3b82f6; }
        .btn-edit:hover { background: rgba(59, 130, 246, 0.1); transform: scale(1.1); color: #3b82f6; }
        .btn-delete { border-color: rgba(239, 68, 68, 0.3); color: #ef4444; }
        .btn-delete:hover { background: rgba(239, 68, 68, 0.1); transform: scale(1.1); color: #ef4444; }

        /* --- Search Box --- */
        .search-wrapper { position: relative; max-width: 300px; width: 100%; }
        .search-wrapper i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-wrapper input { padding-left: 40px; }

        /* --- Modal Styling --- */
        .modal-content { background: var(--modal-bg); backdrop-filter: blur(20px); border: 1px solid var(--border-color); }
        .modal-header .btn-close { filter: invert(var(--bs-btn-close-invert)); } /* Handles dark mode close btn */

        @media (max-width: 991px) { .admin-main { margin-left: 0; padding: 20px; } }
        
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px) } to { opacity: 1; transform: none } }
        .animate-in { animation: fadeUp 0.6s ease forwards; }
    
    /* --- Fix Bootstrap Text Colors for Dark Theme --- */
.text-muted {
    color: var(--text-muted) !important;
}
    </style>
</head>
<body class="admin-body">

    <?php require 'admin_sidebar.php'; ?>

    <div class="admin-main animate-in">
    <div class="admin-page-wrap">
        
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
            <div>
                <p class="mb-1" style="font-size:0.8rem; font-weight:800; text-transform:uppercase; letter-spacing:1.5px; color: var(--green);">Commodity Management</p>
                <h2 class="page-title">Market Pricing Hub</h2>
            </div>
            <div class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 8px 16px; border-radius: 50px; font-weight: 700; border: 1px solid rgba(59, 130, 246, 0.2);">
                <i class="fa-solid fa-clock-rotate-left me-1"></i> Data sync active
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div class="stat-info"><h3 class="mb-0 fw-bold brand-font"><?php echo $total_crops; ?></h3><span class="text-muted small fw-bold">Tracked Commodities</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <div class="stat-info"><h3 class="mb-0 fw-bold brand-font"><?php echo $profit_markets; ?></h3><span class="text-muted small fw-bold">Markets In Profit</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <div class="stat-info"><h3 class="mb-0 fw-bold brand-font"><?php echo $loss_markets; ?></h3><span class="text-muted small fw-bold">Markets In Loss</span></div>
                </div>
            </div>
        </div>

        <?php echo $alert_message; ?>

        <div class="admin-panel-card">
            <h5 class="fw-bold mb-4 brand-font"><i class="fa-solid fa-layer-group text-primary me-2"></i>List New Market Asset</h5>
            <form action="manage_prices.php" method="POST">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">ASSET NAME</label>
                        <input type="text" name="crop_name" class="form-control-custom" placeholder="e.g., Basmati Rice" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">EMOJI / ICON</label>
                        <select name="icon" class="form-select-custom" style="font-size: 1.1rem; font-family: 'Segoe UI Emoji', sans-serif;" required>
                            <option value="" disabled selected>Select Icon</option>
                            <optgroup label="Grains & Cereals">
                                <option value="🌾">🌾 Paddy / Wheat / Oats</option>
                                <option value="🌽">🌽 Corn / Maize</option>
                            </optgroup>
                            <optgroup label="Vegetables (Roots)">
                                <option value="🥔">🥔 Potato</option>
                                <option value="🍠">🍠 Sweet Potato</option>
                                <option value="🧅">🧅 Onion</option>
                                <option value="🧄">🧄 Garlic</option>
                                <option value="🥕">🥕 Carrot</option>
                            </optgroup>
                            <optgroup label="Vegetables (Others)">
                                <option value="🍅">🍅 Tomato</option>
                                <option value="🍆">🍆 Eggplant / Brinjal</option>
                                <option value="🌶️">🌶️ Chilli</option>
                                <option value="🫑">🫑 Bell Pepper / Capsicum</option>
                                <option value="🥒">🥒 Cucumber / Gourd</option>
                                <option value="🥬">🥬 Cabbage / Leafy Greens</option>
                                <option value="🥦">🥦 Cauliflower / Broccoli</option>
                                <option value="🍄">🍄 Mushroom</option>
                            </optgroup>
                            <optgroup label="Pulses & Oilseeds">
                                <option value="🥜">🥜 Peanuts / Groundnuts</option>
                                <option value="🫘">🫘 Beans / Pulses</option>
                                <option value="🫛">🫛 Peas / Soybeans</option>
                                <option value="🌻">🌻 Sunflower / Mustard</option>
                            </optgroup>
                            <optgroup label="Cash Crops & Spices">
                                <option value="🌿">🌿 Jute / Herbs</option>
                                <option value="🎋">🎋 Sugarcane / Bamboo</option>
                                <option value="🍵">🍵 Tea Leaves</option>
                                <option value="☕">☕ Coffee Beans</option>
                                <option value="🌱">🌱 Seeds / Sprouts</option>
                            </optgroup>
                            <optgroup label="Fruits & Plantation">
                                <option value="🥭">🥭 Mango</option>
                                <option value="🍌">🍌 Banana</option>
                                <option value="🥥">🥥 Coconut</option>
                                <option value="🍉">🍉 Watermelon</option>
                                <option value="🍈">🍈 Papaya / Melon</option>
                                <option value="🍍">🍍 Pineapple</option>
                                <option value="🍎">🍎 Apple</option>
                                <option value="🍊">🍊 Orange / Citrus</option>
                                <option value="🍋">🍋 Lemon / Lime</option>
                                <option value="🍇">🍇 Grapes</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">MANDI LOCATION</label>
                        <input type="text" name="mandi_name" class="form-control-custom" placeholder="e.g., Hooghly Hub" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">BASE PRICE (₹)</label>
                        <input type="number" name="price" class="form-control-custom" placeholder="0.00" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold text-muted">UNIT TYPE</label>
                        <input type="text" name="unit" class="form-control-custom" placeholder="e.g., Quintal" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">MARKET TREND (P/L)</label>
                        <select name="trend" class="form-select-custom" required>
                            <option value="stable">➖ Stable / Neutral</option>
                            <option value="up">📈 Profit (Price Up)</option>
                            <option value="down">📉 Loss (Price Down)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">P/L MARGIN TEXT</label>
                        <input type="text" name="price_change" class="form-control-custom" placeholder="e.g., +₹50 (2.5% Profit) or -₹30">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" name="add_price" class="btn-brand w-100"><i class="fa-solid fa-plus"></i> Add Asset</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-panel-card p-0 overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: rgba(16, 185, 129, 0.02);">
                <h5 class="m-0 fw-bold brand-font" style="color: var(--text-main);"><i class="fa-solid fa-database text-success me-2"></i> Active Market Records</h5>
                <div class="search-wrapper m-0">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="tableSearch" class="form-control-custom py-2" placeholder="Search records..." onkeyup="searchTable()">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-custom" id="marketTable">
                    <thead>
                        <tr>
                            <th>Commodity</th>
                            <th>Mandi Source</th>
                            <th>Current Value</th>
                            <th>P/L Margin</th>
                            <th class="text-end">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($all_prices, 0); // Reset pointer for loop ?>
                        <?php while($row = mysqli_fetch_assoc($all_prices)) { ?>
                        <tr class="data-row">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-box"><?php echo htmlspecialchars($row['icon']); ?></div>
                                    <div>
                                        <span class="d-block fw-bold search-target" style="color: var(--text-main);"><?php echo htmlspecialchars($row['crop_name']); ?></span>
                                        <span class="small text-muted fw-semibold">ID: #<?php echo $row['id']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="search-target fw-medium"><i class="fa-solid fa-building-columns text-muted me-2"></i><?php echo htmlspecialchars($row['mandi_name']); ?></td>
                            <td>
                                <?php 
                                    $priceColor = 'stable-text';
                                    if($row['trend'] == 'up') $priceColor = 'profit-text';
                                    if($row['trend'] == 'down') $priceColor = 'loss-text';
                                ?>
                                <span class="fs-5 <?php echo $priceColor; ?>">₹<?php echo number_format($row['price']); ?></span>
                                <span class="text-muted small fw-medium">/ <?php echo htmlspecialchars($row['unit']); ?></span>
                            </td>
                            <td>
                                <?php if($row['trend'] == 'up'): ?>
                                    <span class="badge-trend badge-up"><i class="fa-solid fa-arrow-trend-up"></i> <?php echo htmlspecialchars($row['price_change']); ?></span>
                                <?php elseif($row['trend'] == 'down'): ?>
                                    <span class="badge-trend badge-down"><i class="fa-solid fa-arrow-trend-down"></i> <?php echo htmlspecialchars($row['price_change']); ?></span>
                                <?php else: ?>
                                    <span class="badge-trend badge-stable"><i class="fa-solid fa-minus"></i> Stable</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="action-btn btn-edit me-1" title="Edit Data" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['price']; ?>', '<?php echo $row['trend']; ?>', '<?php echo htmlspecialchars(addslashes($row['price_change'])); ?>', '<?php echo htmlspecialchars(addslashes($row['crop_name'])); ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="manage_prices.php?delete=<?php echo $row['id']; ?>" class="action-btn btn-delete" title="Delete Data" onclick="return confirm('Permanently remove this market data?');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px; padding: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold brand-font" id="modalCropName" style="color: var(--text-main);">Update Price</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="manage_prices.php" method="POST">
                        <input type="hidden" name="edit_id" id="edit_id">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NEW PRICE (₹)</label>
                            <input type="number" name="price" id="edit_price" class="form-control-custom" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">LATEST TREND</label>
                            <select name="trend" id="edit_trend" class="form-select-custom" required>
                                <option value="stable">➖ Stable / Neutral</option>
                                <option value="up">📈 Profit (Price Up)</option>
                                <option value="down">📉 Loss (Price Down)</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">MARGIN TEXT</label>
                            <input type="text" name="price_change" id="edit_price_change" class="form-control-custom">
                        </div>
                        
                        <button type="submit" name="edit_price" class="btn-brand w-100"><i class="fa-solid fa-cloud-arrow-up"></i> Update Database</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live Table Search
        function searchTable() {
            let input = document.getElementById("tableSearch").value.toLowerCase();
            let rows = document.querySelectorAll(".data-row");
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }

        // Open Edit Modal with Data
        function openEditModal(id, price, trend, change, name) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_trend').value = trend;
            document.getElementById('edit_price_change').value = change;
            document.getElementById('modalCropName').innerHTML = "<i class='fa-solid fa-pen-nib text-primary me-2'></i> Update: " + name;
            
            // Handle Dark mode close button invert via JS safely
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const closeBtn = document.querySelector('.modal-header .btn-close');
            if(isDark) { closeBtn.style.filter = 'invert(1)'; } else { closeBtn.style.filter = 'none'; }
            
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
    </script>
</body>
</html>