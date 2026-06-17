<?php
session_start();

// Security Check
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'farmer') {
    header("Location: index.php");
    exit();
}

require 'db.php';

// 1. Fetch User Data
$full_name = $_SESSION['full_name'] ?? 'Farmer';
$first_name = explode(' ', trim($full_name))[0];

// 2. Fetch Latest Market Prices (Dynamic)
$market_query = mysqli_query($conn, "SELECT * FROM market_prices ORDER BY updated_at DESC LIMIT 3");

// 3. Simulated Farm Data (তুমি এগুলো পরে তোমার ডাটাবেস থেকে আনতে পারবে)
$farm_size = '2.5'; 
$active_crop = 'Wheat';
$expected_income = '45,500';
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace | HarvestIQ</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css?v=4.0">
    <!-- Favicon for Browser Tab -->
<link rel="icon" type="image/png" href="assets/logo-192.png">
    <!-- Chart.js for Premium Data Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ==========================================================================
           ULTRA-PREMIUM SAAS DASHBOARD VIBE
           ========================================================================== */
        :root {
            --hiq-bg: #0B1120;
            --hiq-surface: rgba(30, 41, 59, 0.4);
            --hiq-border: rgba(255, 255, 255, 0.08);
            --hiq-text-main: #f8fafc;
            --hiq-text-soft: #94a3b8;
            --hiq-accent: #10b981;
            --hiq-accent-glow: rgba(16, 185, 129, 0.15);
            --hiq-card-hover: rgba(30, 41, 59, 0.7);
        }

        body {
            background-color: var(--hiq-bg);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(16, 185, 129, 0.08), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.08), transparent 25%);
            font-family: 'Inter', sans-serif;
            color: var(--hiq-text-main);
            overflow-x: hidden;
        }

        .dashboard-wrapper {
            max-width: 1440px;
            margin: 0 auto;
            padding: 120px 3% 80px;
            position: relative;
            z-index: 1;
        }

        /* 🌟 Header & Welcome */
        .dash-header {
            display: flex; justify-content: space-between; align-items: flex-end;
            margin-bottom: 35px; flex-wrap: wrap; gap: 20px;
        }

        .dash-greeting h1 {
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.8rem; font-weight: 800;
            margin: 0 0 5px 0; background: linear-gradient(to right, #ffffff, #a7f3d0);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .dash-greeting p { color: var(--hiq-text-soft); font-size: 1.1rem; margin: 0; }

        /* 🌟 Ultra-Premium Bento Grid System */
        .premium-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 24px;
        }

        .glass-panel {
            background: var(--hiq-surface);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--hiq-border);
            border-radius: 24px;
            padding: 28px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
        }

        .glass-panel:hover {
            transform: translateY(-5px);
            background: var(--hiq-card-hover);
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4), inset 0 0 0 1px rgba(16, 185, 129, 0.1);
        }

        /* 🌟 Smart AI Advisor (Spans 12 or 8 cols) */
        .panel-advisor {
            grid-column: span 12;
            display: flex; align-items: center; gap: 25px;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(11, 17, 32, 0.8));
            border-left: 4px solid var(--hiq-accent);
        }
        .adv-icon { font-size: 2.5rem; color: var(--hiq-accent); background: rgba(16, 185, 129, 0.1); padding: 15px; border-radius: 20px; }
        .adv-text h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.4rem; margin-bottom: 5px; }
        .adv-text p { color: #cbd5e1; margin: 0; font-size: 1.05rem; }

        /* 🌟 Quick Stats (Span 4 cols) */
        .panel-stat { grid-column: span 4; display: flex; flex-direction: column; justify-content: space-between; }
        .stat-head { display: flex; justify-content: space-between; margin-bottom: 15px; color: var(--hiq-text-soft); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;}
        .stat-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.4rem; font-weight: 800; margin-bottom: 5px; color: #fff;}
        .stat-desc { font-size: 0.9rem; color: var(--hiq-text-soft); }
        .stat-icon { font-size: 1.5rem; }

        /* 🌟 Main Content Area: Chart & Lists */
        .panel-chart { grid-column: span 8; padding: 25px; }
        .panel-tasks { grid-column: span 4; }
        
        .section-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 800; margin-bottom: 20px; color: #fff; display: flex; justify-content: space-between;}

        /* Task List (Farmer Friendly) */
        .task-list { display: flex; flex-direction: column; gap: 12px; }
        .task-item { display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.03); padding: 12px 15px; border-radius: 12px; border: 1px solid var(--hiq-border); }
        .task-item input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--hiq-accent); cursor: pointer; }
        .task-text { font-size: 0.95rem; font-weight: 500; color: #e2e8f0; }

        /* Market Mini-cards */
        .market-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--hiq-border); }
        .market-row:last-child { border-bottom: none; }
        .m-crop { font-weight: 700; color: #fff; }
        .m-price { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--hiq-accent); }

        @media (max-width: 1024px) {
            .panel-stat { grid-column: span 6; }
            .panel-chart, .panel-tasks { grid-column: span 12; }
        }
        @media (max-width: 768px) {
            .panel-stat { grid-column: span 12; }
            .panel-advisor { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>

    <div class="dashboard-wrapper">
        
        <!-- 1. HEADER SECTION -->
        <div class="dash-header">
            <div class="dash-greeting">
                <h1>Hello, <?php echo htmlspecialchars($first_name); ?> 👋</h1>
                <p>Your farm's digital command center. Everything looks good today.</p>
            </div>
            <div style="text-align: right;">
                <p style="color: var(--hiq-text-soft); font-weight: 600; margin-bottom: 5px;"><i class="fa-regular fa-calendar text-success me-2"></i> <span id="currentDate"></span></p>
                <a href="crop_recommendation.php" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm"><i class="fa-solid fa-plus me-2"></i> Add Farm Data</a>
            </div>
        </div>

        <!-- 2. BENTO GRID SYSTEM -->
        <div class="premium-grid">
            
            <!-- AI Advisory (Full Width) -->
            <div class="glass-panel panel-advisor">
                <div class="adv-icon"><i class="fa-solid fa-cloud-bolt"></i></div>
                <div class="adv-text">
                    <h3>Smart AI Advice <span class="badge bg-danger ms-2" style="font-size: 0.7rem;">Weather Alert</span></h3>
                    <p>Heavy rainfall is predicted tomorrow afternoon. Save money and water by skipping irrigation today. Do not spray fertilizers until the weather clears.</p>
                </div>
            </div>

            <!-- Stat 1: Farm Size -->
            <div class="glass-panel panel-stat">
                <div class="stat-head">
                    <span>Farm Size</span>
                    <i class="fa-solid fa-map-location-dot stat-icon text-info"></i>
                </div>
                <div>
                    <div class="stat-val"><?php echo $farm_size; ?> <span style="font-size: 1rem; color: var(--hiq-text-soft);">Acres</span></div>
                    <div class="stat-desc">Total registered land</div>
                </div>
            </div>

            <!-- Stat 2: Active Crop -->
            <div class="glass-panel panel-stat">
                <div class="stat-head">
                    <span>Currently Growing</span>
                    <i class="fa-solid fa-wheat-awn stat-icon text-warning"></i>
                </div>
                <div>
                    <div class="stat-val"><?php echo $active_crop; ?></div>
                    <div class="stat-desc">Next harvest in ~45 days</div>
                </div>
            </div>

            <!-- Stat 3: Expected Income -->
            <div class="glass-panel panel-stat">
                <div class="stat-head">
                    <span>Expected Income</span>
                    <i class="fa-solid fa-indian-rupee-sign stat-icon text-success"></i>
                </div>
                <div>
                    <div class="stat-val text-success">₹<?php echo $expected_income; ?></div>
                    <div class="stat-desc">Based on current local prices</div>
                </div>
            </div>

            <!-- Premium Chart Section -->
            <div class="glass-panel panel-chart">
                <div class="section-title">
                    <span><i class="fa-solid fa-chart-line text-success me-2"></i> Market Price Trend (Wheat)</span>
                </div>
                <!-- Canvas for Chart.js -->
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="marketChart"></canvas>
                </div>
            </div>

            <!-- To-Do & Quick Market Sidebar -->
            <div class="glass-panel panel-tasks">
                <div class="section-title">
                    <span><i class="fa-solid fa-list-check text-success me-2"></i> Today's Tasks</span>
                </div>
                <div class="task-list mb-4">
                    <label class="task-item">
                        <input type="checkbox" checked>
                        <span class="task-text" style="text-decoration: line-through; opacity: 0.7;">Check soil moisture level</span>
                    </label>
                    <label class="task-item">
                        <input type="checkbox">
                        <span class="task-text">Clean irrigation pipes</span>
                    </label>
                    <label class="task-item">
                        <input type="checkbox">
                        <span class="task-text">Review local crop prices</span>
                    </label>
                </div>

                <div class="section-title mt-4" style="font-size: 1rem;">
                    <span>Live Local Prices</span>
                    <a href="market_prices.php" style="font-size: 0.8rem; color: var(--hiq-accent); text-decoration: none;">View All</a>
                </div>
                
                <div class="market-list">
                    <?php 
                    if($market_query && mysqli_num_rows($market_query) > 0) {
                        while($crop = mysqli_fetch_assoc($market_query)) {
                    ?>
                        <div class="market-row">
                            <span class="m-crop"><?php echo htmlspecialchars($crop['icon'] . ' ' . $crop['crop_name']); ?></span>
                            <span class="m-price">₹<?php echo number_format($crop['price']); ?> <span style="font-size: 0.7rem; color: var(--hiq-text-soft);">/ <?php echo htmlspecialchars($crop['unit']); ?></span></span>
                        </div>
                    <?php 
                        }
                    } else {
                        echo "<p style='color: var(--hiq-text-soft); font-size: 0.9rem;'>No prices updated today.</p>";
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <?php include "footer.php" ?>

    <!-- Scripts -->
    <script>
        // 1. Live Date
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').innerText = new Date().toLocaleDateString('en-US', options);

        // 2. Chart.js Implementation (Premium Graphic)
        const ctx = document.getElementById('marketChart').getContext('2d');
        
        // Gradient for chart area
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');   
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        const marketChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'This Week'],
                datasets: [{
                    label: 'Price per Quintal (₹)',
                    data: [2100, 2150, 2130, 2200, 2280], // Dummy dynamic data
                    backgroundColor: gradient,
                    borderColor: '#10b981',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                        bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: { 
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter', size: 12 } }
                    },
                    y: { 
                        grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter', size: 12 }, stepSize: 50 }
                    }
                },
                interaction: { intersect: false, mode: 'index' },
            }
        });
    </script>
</body>
</html>