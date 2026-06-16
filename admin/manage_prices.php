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
        $alert_message = '<div class="alert alert-success shadow-sm rounded-4"><i class="fa-solid fa-circle-check me-2"></i> Market asset successfully listed!</div>';
    } else {
        $alert_message = '<div class="alert alert-danger shadow-sm rounded-4"><i class="fa-solid fa-circle-exclamation me-2"></i> Failed to add asset.</div>';
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
        $alert_message = '<div class="alert alert-info shadow-sm rounded-4"><i class="fa-solid fa-arrows-rotate me-2"></i> Market data updated successfully!</div>';
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

    <?php require 'admin_sidebar.php'; ?>

    <div class="admin-main">
    <div class="admin-page-wrap">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <p class="text-muted mb-1 fw-bold" style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 0.8rem;">Commodity Management</p>
                <h2 class="page-title">Market Pricing Hub</h2>
            </div>
            <div class="text-end text-muted small fw-medium">
                <i class="fa-solid fa-clock me-1"></i> Data sync active
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #E0F2FE; color: #0284C7;"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div><h3 class="mb-0 fw-bold brand-font"><?php echo $total_crops; ?></h3><span class="text-muted small fw-bold">TRACKED COMMODITIES</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #D1FAE5; color: #059669;"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <div><h3 class="mb-0 fw-bold brand-font"><?php echo $profit_markets; ?></h3><span class="text-muted small fw-bold">MARKETS IN PROFIT</span></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #FEE2E2; color: #DC2626;"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <div><h3 class="mb-0 fw-bold brand-font"><?php echo $loss_markets; ?></h3><span class="text-muted small fw-bold">MARKETS IN LOSS</span></div>
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
    <select name="icon" class="form-select-custom" style="font-size: 1.1rem;" required>
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
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h5 class="m-0 fw-bold brand-font">Active Market Records</h5>
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
                                        <span class="d-block fw-bold text-dark search-target"><?php echo htmlspecialchars($row['crop_name']); ?></span>
                                        <span class="small text-muted">ID: #<?php echo $row['id']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="search-target"><i class="fa-solid fa-building-columns text-muted me-2"></i><?php echo htmlspecialchars($row['mandi_name']); ?></td>
                            <td>
                                <?php 
                                    $priceColor = 'stable-text';
                                    if($row['trend'] == 'up') $priceColor = 'profit-text';
                                    if($row['trend'] == 'down') $priceColor = 'loss-text';
                                ?>
                                <span class="fs-5 <?php echo $priceColor; ?>">₹<?php echo number_format($row['price']); ?></span>
                                <span class="text-muted small fw-normal">/ <?php echo htmlspecialchars($row['unit']); ?></span>
                            </td>
                            <td>
                                <?php if($row['trend'] == 'up'): ?>
                                    <span class="badge-trend badge-up"><i class="fa-solid fa-arrow-up"></i> <?php echo htmlspecialchars($row['price_change']); ?></span>
                                <?php elseif($row['trend'] == 'down'): ?>
                                    <span class="badge-trend badge-down"><i class="fa-solid fa-arrow-down"></i> <?php echo htmlspecialchars($row['price_change']); ?></span>
                                <?php else: ?>
                                    <span class="badge-trend badge-stable"><i class="fa-solid fa-minus"></i> Stable</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <button class="action-btn btn-edit me-1" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['price']; ?>', '<?php echo $row['trend']; ?>', '<?php echo htmlspecialchars(addslashes($row['price_change'])); ?>', '<?php echo htmlspecialchars(addslashes($row['crop_name'])); ?>')">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <a href="manage_prices.php?delete=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Permanently remove this market data?');">
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
            <div class="modal-content" style="border-radius: 24px; border: none; padding: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold brand-font" id="modalCropName">Update Price</h5>
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
                                <option value="stable">Stable / Neutral</option>
                                <option value="up">Profit (Price Up)</option>
                                <option value="down">Loss (Price Down)</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">MARGIN TEXT</label>
                            <input type="text" name="price_change" id="edit_price_change" class="form-control-custom">
                        </div>
                        
                        <button type="submit" name="edit_price" class="btn-brand w-100"><i class="fa-solid fa-check-double"></i> Save Changes</button>
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
            document.getElementById('modalCropName').innerText = "Update: " + name;
            
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
        }
    </script>
</body>
</html>