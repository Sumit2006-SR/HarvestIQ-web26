<?php
session_start();

// Security Check: Only logged-in farmers can access this page
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'farmer') {
    header("Location: index.php");
    exit();
}

require 'db.php';

// Fetch user data from session
$full_name = $_SESSION['full_name'] ?? 'Farmer';
$first_name = explode(' ', trim($full_name))[0];

// Fetch 3 latest market prices for the dashboard preview
$market_preview_query = mysqli_query($conn, "SELECT * FROM market_prices ORDER BY updated_at DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | HarvestIQ</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
 :root {
    --bg-deep: #f8fafc; 
    --bg-emerald: #d1fae5; 
    --glass-card: rgba(255, 255, 255, 0.8);
    --glass-border: rgba(0, 0, 0, 0.05);
    --primary-accent: #10b981;
    --neon-glow: #059669;
    --text-light: #0f172a;
    --text-gray: #475569;
}

 [data-theme="dark"] {
    --bg-deep: #020617; 
    --bg-emerald: #064e3b; 
    --glass-card: rgba(15, 23, 42, 0.6);
    --glass-border: rgba(255, 255, 255, 0.08);
    --primary-accent: #10b981;
    --neon-glow: #34d399;
    --text-light: #f8fafc;
    --text-gray: #94a3b8;
}

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at top left, var(--bg-emerald) 0%, var(--bg-deep) 70%);
            background-attachment: fixed;
            color: var(--text-light);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 120px 20px 60px; /* Top padding to clear sticky nav */
        }

        /* --- Hero Welcome Section --- */
        .welcome-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-text h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2.8rem;
            font-weight: 800;
            margin: 0 0 5px 0;
            background: linear-gradient(to right, #fff, #a7f3d0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .welcome-text p {
            color: var(--text-gray);
            font-size: 1.1rem;
            margin: 0;
        }

        .weather-widget {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(12px);
        }

        .weather-icon { font-size: 2.5rem; color: #fbbf24; }
        .weather-info h4 { margin: 0; font-size: 1.5rem; font-weight: 700; color: #fff; }
        .weather-info p { margin: 0; font-size: 0.85rem; color: var(--text-gray); }

        /* --- AI Recommendation Banner --- */
        .ai-banner {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(6, 78, 59, 0.4) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 24px;
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);
        }

        .ai-banner::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(52, 211, 153, 0.1) 0%, transparent 60%);
            animation: rotateGlow 10s linear infinite;
        }

        @keyframes rotateGlow { 100% { transform: rotate(360deg); } }

        .ai-icon {
            width: 60px; height: 60px;
            background: rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: var(--neon-glow); z-index: 1;
        }

        .ai-content { z-index: 1; }
        .ai-content h3 { margin: 0 0 8px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.3rem; color: #fff; display: flex; align-items: center; gap: 8px;}
        .ai-content p { margin: 0; color: #d1fae5; font-size: 0.95rem; line-height: 1.5; }
        .ai-badge { background: var(--primary-accent); color: #fff; padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }

        /* --- Quick Stats Grid --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--glass-card);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(16px);
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-card:hover { transform: translateY(-5px); border-color: rgba(255,255,255,0.15); background: rgba(30, 41, 59, 0.8); }

        .stat-icon-box {
            width: 54px; height: 54px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }

        .stat-info h4 { margin: 0; font-size: 1.8rem; font-weight: 800; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;}
        .stat-info span { font-size: 0.85rem; color: var(--text-gray); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;}

        /* --- Market Preview Section --- */
        .section-header {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
        }
        .section-header h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; margin: 0; }
        .btn-view-all { background: rgba(255,255,255,0.05); color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 600; border: 1px solid var(--glass-border); transition: 0.3s; }
        .btn-view-all:hover { background: var(--primary-accent); border-color: var(--primary-accent); }

        .market-list { display: flex; flex-direction: column; gap: 15px; }
        .market-item {
            background: var(--glass-card); border: 1px solid var(--glass-border); border-radius: 16px;
            padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;
            backdrop-filter: blur(10px); transition: 0.3s;
        }
        .market-item:hover { background: rgba(255,255,255,0.05); transform: translateX(5px); }
        
        .market-crop { display: flex; align-items: center; gap: 15px; }
        .market-icon { font-size: 1.8rem; }
        .market-name { font-weight: 700; font-size: 1.1rem; color: #fff; display: block; }
        .market-loc { font-size: 0.8rem; color: var(--text-gray); }
        
        .market-price-box { text-align: right; }
        .market-price { font-size: 1.3rem; font-weight: 800; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .trend-up { color: var(--neon-glow); font-size: 0.85rem; font-weight: 600; }
        .trend-down { color: #f87171; font-size: 0.85rem; font-weight: 600; }
        .trend-stable { color: #cbd5e1; font-size: 0.85rem; font-weight: 600; }

        @media (max-width: 768px) {
            .welcome-section { flex-direction: column; align-items: flex-start; }
            .ai-banner { flex-direction: column; text-align: center; }
            .market-item { flex-direction: column; align-items: flex-start; gap: 15px; }
            .market-price-box { text-align: left; }
        }
    </style>
</head>
<body>

    <?php include 'nav.php'; ?>

    <div class="dashboard-container">
        
        <div class="welcome-section">
            <div class="welcome-text">
                <p id="currentDate">Loading date...</p>
                <h1>Welcome back, <?php echo htmlspecialchars($first_name); ?>! 👋</h1>
                <p>Here is your daily agricultural overview and insights.</p>
            </div>
            
            <div class="weather-widget">
                <div class="weather-icon"><i class="fa-solid fa-cloud-sun"></i></div>
                <div class="weather-info">
                    <h4>32°C</h4>
                    <p>Partly Cloudy • Humidity 65%</p>
                </div>
            </div>
        </div>

        <div class="ai-banner">
            <div class="ai-icon"><i class="fa-solid fa-microchip"></i></div>
            <div class="ai-content">
                <h3>HarvestIQ AI Assistant <span class="ai-badge">New Alert</span></h3>
                <p><strong>Weather Advisory:</strong> Light rain is expected in your region tomorrow afternoon. It is recommended to delay pesticide application until the weather clears to prevent wash-off.</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-box" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div class="stat-info">
                    <h4>2.5 Ac</h4>
                    <span>Registered Land</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="fa-solid fa-seedling"></i>
                </div>
                <div class="stat-info">
                    <h4>Paddy</h4>
                    <span>Active Crop Cycle</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon-box" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="stat-info">
                    <h4>2 Alerts</h4>
                    <span>Unread Advisories</span>
                </div>
            </div>
        </div>

        <div class="section-header">
            <h2>📈 Local Market Highlights</h2>
            <a href="market_prices.php" class="btn-view-all">View Full Board</a>
        </div>
        
        <div class="market-list">
            <?php 
            if($market_preview_query && mysqli_num_rows($market_preview_query) > 0) {
                while($crop = mysqli_fetch_assoc($market_preview_query)) {
                    $trendIcon = ''; $trendClass = '';
                    if($crop['trend'] == 'up') { $trendIcon = '↑'; $trendClass = 'trend-up'; }
                    elseif($crop['trend'] == 'down') { $trendIcon = '↓'; $trendClass = 'trend-down'; }
                    else { $trendIcon = '-'; $trendClass = 'trend-stable'; }
            ?>
                <div class="market-item">
                    <div class="market-crop">
                        <div class="market-icon"><?php echo htmlspecialchars($crop['icon']); ?></div>
                        <div>
                            <span class="market-name"><?php echo htmlspecialchars($crop['crop_name']); ?></span>
                            <span class="market-loc"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($crop['mandi_name']); ?></span>
                        </div>
                    </div>
                    <div class="market-price-box">
                        <div class="market-price">₹<?php echo number_format($crop['price']); ?> <span style="font-size: 0.9rem; font-weight:500; color: #94a3b8;">/ <?php echo htmlspecialchars($crop['unit']); ?></span></div>
                        <div class="<?php echo $trendClass; ?>"><?php echo $trendIcon; ?> <?php echo htmlspecialchars($crop['price_change'] ?: 'Stable'); ?> today</div>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<p style='color: #94a3b8;'>No market data available yet.</p>";
            }
            ?>
        </div>

    </div>

    <script>
        // Set dynamic current date
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').innerText = new Date().toLocaleDateString('en-US', dateOptions);
    </script>

    <?php include "footer.php" ?>
</body>
</html>
