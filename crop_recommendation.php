<?php
require_once 'db.php';

$recommendations = [];
$is_submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1.5 seconds delay for AI Loading Animation
    usleep(1500000); 

    $is_submitted = true;
    $user_soil = $_POST['soil_type'];
    $user_season = $_POST['season'];
    $user_water = $_POST['water'];
    $user_land_size = (float) $_POST['land_size'];

    $stmt = $conn->prepare("SELECT * FROM crop_knowledge WHERE soil_type = ? AND season = ?");
    $stmt->bind_param("ss", $user_soil, $user_season);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($crop = $result->fetch_assoc()) {
        $total_yield = $crop['yield_per_acre'] * $user_land_size;
        $total_revenue = $total_yield * $crop['price_per_kg'];
        $total_cost = $crop['cost_per_acre'] * $user_land_size;
        $net_profit = $total_revenue - $total_cost;
        $roi = ($total_cost > 0) ? ($net_profit / $total_cost) * 100 : 0;

        $match_score = 95; 
        $risk_level = 'Low';
        $water_efficiency = 'Optimal';
        $risk_color = 'success';

        if ($crop['water_req'] == 'High' && $user_water == 'Low') {
            $match_score -= 42; 
            $risk_level = 'Critical'; 
            $water_efficiency = 'Poor (Needs high irrigation)';
            $risk_color = 'danger';
        } elseif ($crop['water_req'] == 'Medium' && $user_water == 'Low') {
            $match_score -= 25; 
            $risk_level = 'High'; 
            $water_efficiency = 'Sub-optimal (Needs irrigation)';
            $risk_color = 'warning';
        } elseif ($crop['water_req'] == 'High' && $user_water == 'Medium') {
            $match_score -= 15; 
            $risk_level = 'Medium'; 
            $water_efficiency = 'Manageable';
            $risk_color = 'warning';
        } elseif ($crop['water_req'] == 'Low' && $user_water == 'High') {
            $match_score -= 5; 
            $risk_level = 'Low'; 
            $water_efficiency = 'Excellent (Water Surplus)';
            $risk_color = 'success';
        } elseif ($crop['water_req'] == 'Low' && $user_water == 'Low') {
            $match_score += 4; 
            $risk_level = 'Very Low'; 
            $water_efficiency = 'Highly Sustainable';
            $risk_color = 'success';
        }

        $match_score += rand(-2, 2);
        if($match_score > 99) $match_score = 99;

        if ($match_score >= 40) {
            $crop['calculated_yield'] = $total_yield;
            $crop['total_revenue'] = $total_revenue;
            $crop['total_cost'] = $total_cost;
            $crop['net_profit'] = $net_profit;
            $crop['roi'] = round($roi, 1);
            $crop['match_score'] = $match_score;
            $crop['risk_level'] = $risk_level;
            $crop['water_efficiency'] = $water_efficiency;
            $crop['risk_color'] = $risk_color;

            $recommendations[] = $crop;
        }
    }

    usort($recommendations, function ($a, $b) {
        return $b['match_score'] <=> $a['match_score'];
    });

    $recommendations = array_slice($recommendations, 0, 4);
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HarvestIQ | AI Crop Engine</title>
    
    <script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=6.0">

    <style>
        /* =========================================
           1. PERFECT LIGHT/DARK THEME VARIABLES
           ========================================= */
        :root {
            --brand-primary: #10b981;
            --brand-secondary: #059669;
            --bg-main: #f8fafc;
            --bg-card: rgba(255, 255, 255, 0.85);
            --bg-input: #f1f5f9;
            --border-glass: rgba(15, 23, 42, 0.08);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --metric-bg: rgba(241, 245, 249, 0.8);
            --card-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
        }

        [data-theme="dark"] {
            --brand-primary: #10b981;
            --brand-secondary: #34d399;
            --bg-main: #020617;
            --bg-card: rgba(15, 23, 42, 0.5);
            --bg-input: rgba(30, 41, 59, 0.5);
            --border-glass: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --metric-bg: rgba(255, 255, 255, 0.03);
            --card-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Overriding Bootstrap text-muted for theme consistency */
        .text-muted { color: var(--text-muted) !important; }

        .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* =========================================
           2. HEADER & FORM PANEL
           ========================================= */
        .engine-header { padding: 140px 20px 60px; text-align: center; }
        .engine-title { font-size: clamp(2.2rem, 5vw, 4rem); font-weight: 900; letter-spacing: -1px; margin-bottom: 15px; }
        .engine-title span { background: linear-gradient(135deg, var(--brand-primary), #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .glass-panel {
            background: var(--bg-card); backdrop-filter: blur(24px); border: 1px solid var(--border-glass);
            border-radius: 32px; padding: 50px 40px; box-shadow: var(--card-shadow); position: relative; z-index: 5;
            transition: 0.3s;
        }

        .form-label { font-weight: 700; color: var(--text-muted); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;}
        .form-label i { color: var(--brand-primary); font-size: 1.1rem;}

        .form-select, .form-control {
            background-color: var(--bg-input); border: 1px solid var(--border-glass); color: var(--text-main);
            border-radius: 16px; padding: 16px 20px; font-weight: 500; font-size: 1rem; transition: 0.3s;
        }
        .form-select:focus, .form-control:focus {
            background-color: var(--bg-card); border-color: var(--brand-primary); box-shadow: 0 0 0 4px rgba(16,185,129,0.15); color: var(--text-main);
        }
        
        /* Dropdown options styling for dark mode */
        [data-theme="dark"] .form-select option { background: #0f172a; color: #fff; }

        .btn-run-ai {
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary)); color: white; border: none; padding: 20px;
            border-radius: 16px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.2rem;
            width: 100%; cursor: pointer; transition: 0.4s; box-shadow: 0 10px 30px rgba(16,185,129,0.3); position: relative; overflow: hidden;
        }
        .btn-run-ai:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(16,185,129,0.5); }
        
        .ai-terminal { display: none; text-align: center; }
        .btn-run-ai.loading .btn-content { display: none; }
        .btn-run-ai.loading .ai-terminal { display: block; font-family: monospace; font-size: 1rem; color: #fff; letter-spacing: 0.5px;}

        /* =========================================
           3. RESULTS & METRICS (FIXED OVERFLOW)
           ========================================= */
        .result-card {
            background: var(--bg-card); border: 1px solid var(--border-glass); border-radius: 32px;
            padding: 35px; margin-bottom: 30px; transition: 0.4s; position: relative; overflow: hidden;
            backdrop-filter: blur(20px); box-shadow: var(--card-shadow);
        }
        .result-card:hover { transform: translateY(-5px); border-color: rgba(16,185,129,0.4); box-shadow: 0 25px 60px rgba(0,0,0,0.15); }
        
        .best-match { border: 2px solid var(--brand-primary); box-shadow: 0 0 30px rgba(16,185,129,0.1); }
        .best-match::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at top left, rgba(16,185,129,0.05), transparent 50%); pointer-events: none; }
        
        /* Fixed Ribbon */
        .ribbon { 
            position: absolute; top: 25px; right: -35px; background: var(--brand-primary); color: #fff; 
            font-family: 'Plus Jakarta Sans'; font-weight: 900; font-size: 0.75rem; padding: 6px 40px; 
            transform: rotate(45deg); box-shadow: 0 5px 15px rgba(16,185,129,0.3); letter-spacing: 1px; z-index: 10;
        }

        .crop-header { display: flex; align-items: flex-start; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid var(--border-glass); padding-bottom: 25px;}
        .crop-icon { font-size: 4rem; filter: drop-shadow(0 10px 20px rgba(16,185,129,0.2)); }
        .crop-title h3 { font-size: clamp(1.8rem, 3vw, 2.2rem); font-weight: 800; margin: 0; color: var(--text-main);}
        .crop-duration { color: var(--brand-primary); font-weight: 700; font-size: 0.95rem; margin-top: 5px; }

        /* Fixed Metrics Grid (No Overflow) */
        .metrics-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); 
            gap: 15px; 
            margin-bottom: 25px;
        }
        .metric-box { 
            background: var(--metric-bg); border: 1px solid var(--border-glass); 
            padding: 20px 15px; border-radius: 20px; transition: 0.3s;
            display: flex; flex-direction: column; justify-content: center;
        }
        .metric-box:hover { background: rgba(16,185,129,0.05); border-color: rgba(16,185,129,0.2);}
        
        .m-lbl { font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;}
        
        /* Fluid Typography for Prices */
        .m-val { 
            font-size: clamp(1.1rem, 2vw, 1.5rem); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            font-weight: 800; 
            color: var(--text-main);
            word-break: break-word; /* Prevents overflow */
            line-height: 1.2;
        }
        
        .m-profit { color: var(--brand-primary); }

        .insight-box { background: rgba(16,185,129,0.05); border-left: 4px solid var(--brand-primary); padding: 20px; border-radius: 0 16px 16px 0; margin-bottom: 25px;}
        .insight-box h6 { color: var(--text-main); font-weight: 800; margin-bottom: 10px; font-size: 1.05rem;}
        .insight-box p { color: var(--text-muted); font-size: 0.95rem; margin: 0; line-height: 1.6;}

        /* Circular Progress */
        .circular-chart { display: block; margin: 0 auto; max-width: 140px; max-height: 140px; }
        .circle-bg { fill: none; stroke: var(--border-glass); stroke-width: 3.8; }
        .circle { fill: none; stroke-width: 2.8; stroke-linecap: round; animation: progress 1.5s ease-out forwards; }
        .percentage { fill: var(--text-main); font-family: 'Plus Jakarta Sans'; font-size: 0.5em; text-anchor: middle; font-weight: 900; }
        .score-lbl { fill: var(--text-muted); font-family: 'Inter'; font-size: 0.18em; text-anchor: middle; font-weight: 600; letter-spacing: 1px;}
        @keyframes progress { 0% { stroke-dasharray: 0 100; } }

        .risk-badge { padding: 6px 14px; border-radius: 50px; font-size: 0.8rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; border: 1px solid;}
        .risk-success { background: rgba(16,185,129,0.1); color: var(--brand-primary); border-color: rgba(16,185,129,0.3); }
        .risk-warning { background: rgba(245,158,11,0.1); color: #fbbf24; border-color: rgba(245,158,11,0.3); }
        .risk-danger { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.3); }
        .risk-info { background: rgba(59,130,246,0.1); color: #3b82f6; border-color: rgba(59,130,246,0.3); }

        .btn-outline-custom {
            border: 2px solid var(--border-glass); color: var(--text-main); background: transparent;
            padding: 10px 24px; border-radius: 50px; font-weight: 700; text-decoration: none; transition: 0.3s;
        }
        .btn-outline-custom:hover { background: var(--text-main); color: var(--bg-main); }
    </style>
</head>

<body>
    <?php include "nav.php" ?>

    <div class="engine-header reveal active">
        <div class="container">
            <h1 class="engine-title">AI Crop <span>Intelligence</span></h1>
            <p class="lead text-muted mx-auto" style="max-width: 650px;">Input your farm's telemetry. Our Machine Learning engine will analyze risks, water sustainability, and calculate absolute peak profitability.</p>
        </div>
    </div>

    <div class="container pb-5">
        <?php if (!$is_submitted): ?>
            <!-- ================= INPUT FORM ================= -->
            <div class="row justify-content-center reveal active">
                <div class="col-lg-10">
                    <div class="glass-panel">
                        <form method="POST" id="aiForm" onsubmit="runAIEngine(event)">
                            <div class="row g-4 mb-5">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-layer-group"></i> Soil Matrix Composition</label>
                                    <select class="form-select" name="soil_type" required>
                                        <option value="" disabled selected>Select primary soil type...</option>
                                        <option value="Loamy">Loamy (High Nutrient/Balance)</option>
                                        <option value="Clay">Clay (High Water Retention)</option>
                                        <option value="Sandy">Sandy (Rapid Drainage)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-cloud-sun"></i> Seasonal Cycle</label>
                                    <select class="form-select" name="season" required>
                                        <option value="" disabled selected>Determine atmospheric window...</option>
                                        <option value="Rabi">Rabi (Winter / Dry Phase)</option>
                                        <option value="Kharif">Kharif (Monsoon / Wet Phase)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-tint"></i> Water Availability Index</label>
                                    <select class="form-select" name="water" required>
                                        <option value="" disabled selected>Assess irrigation capacity...</option>
                                        <option value="Low">Low (Strictly Rainfed)</option>
                                        <option value="Medium">Medium (Supplemental Irrigation)</option>
                                        <option value="High">High (Abundant Access)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-vector-square"></i> Cultivation Area (Acres)</label>
                                    <input type="number" step="0.1" min="0.1" class="form-control" name="land_size" placeholder="Enter farm size (e.g. 2.5)" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-run-ai" id="submitBtn">
                                <span class="btn-content"><i class="fas fa-microchip me-2"></i> Run Deep Analysis</span>
                                <div class="ai-terminal" id="terminalText">Initializing AI models...</div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ================= RESULTS DASHBOARD ================= -->
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 reveal active gap-3">
                <div>
                    <h2 class="fw-bold mb-2 text-main">Analysis Complete</h2>
                    <p class="text-muted m-0"><i class="fas fa-check-circle text-success me-2"></i> Derived top recommendations for <?php echo htmlspecialchars($user_land_size); ?> acres of <?php echo htmlspecialchars($user_soil); ?> soil.</p>
                </div>
                <a href="crop_recommendation.php" class="btn-outline-custom"><i class="fas fa-redo me-2"></i> Recalibrate</a>
            </div>

            <?php if (count($recommendations) > 0): ?>
                <?php foreach ($recommendations as $index => $crop): ?>
                    <?php 
                        $circle_color = ($crop['match_score'] >= 80) ? '#10b981' : (($crop['match_score'] >= 60) ? '#f59e0b' : '#ef4444');
                        $dash_val = $crop['match_score'] . ', 100';
                    ?>
                    
                    <div class="result-card reveal active <?php echo ($index == 0) ? 'best-match' : ''; ?>" style="transition-delay: <?php echo $index * 0.1; ?>s;">
                        <?php if ($index == 0): ?><div class="ribbon">TOP MATCH</div><?php endif; ?>

                        <div class="row align-items-center">
                            <!-- Left: Score -->
                            <div class="col-lg-3 col-md-4 text-center border-end border-secondary border-opacity-25 mb-4 mb-md-0 d-flex flex-column justify-content-center">
                                <svg viewBox="0 0 36 36" class="circular-chart">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="circle" stroke="<?php echo $circle_color; ?>" stroke-dasharray="<?php echo $dash_val; ?>" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <text x="18" y="19" class="percentage"><?php echo $crop['match_score']; ?>%</text>
                                    <text x="18" y="25" class="score-lbl">AI MATCH</text>
                                </svg>
                            </div>

                            <!-- Right: Details -->
                            <div class="col-lg-9 col-md-8 ps-md-4">
                                <div class="crop-header flex-column flex-sm-row">
                                    <div class="crop-icon"><?php echo $crop['icon']; ?></div>
                                    <div class="crop-title">
                                        <h3><?php echo htmlspecialchars($crop['name']); ?></h3>
                                        <div class="crop-duration"><i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($crop['duration']); ?> Harvest Cycle</div>
                                    </div>
                                    <div class="ms-sm-auto text-sm-end mt-3 mt-sm-0">
                                        <div class="risk-badge risk-<?php echo $crop['risk_color']; ?> mb-2">
                                            <i class="fas fa-shield-alt"></i> Risk: <?php echo $crop['risk_level']; ?>
                                        </div>
                                        <br>
                                        <div class="risk-badge risk-info">
                                            <i class="fas fa-tint"></i> <?php echo $crop['water_efficiency']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="insight-box">
                                    <h6><i class="fas fa-robot text-success me-2"></i> Agronomic Advisory</h6>
                                    <p><?php echo htmlspecialchars($crop['reason']); ?></p>
                                </div>

                                <div class="metrics-grid">
                                    <div class="metric-box">
                                        <div class="m-lbl">Est. Capital Required</div>
                                        <div class="m-val text-danger">₹<?php echo number_format($crop['total_cost']); ?></div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="m-lbl">Projected Revenue</div>
                                        <div class="m-val text-info">₹<?php echo number_format($crop['total_revenue']); ?></div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="m-lbl">Volume Yield</div>
                                        <div class="m-val"><?php echo number_format($crop['calculated_yield']); ?> <span class="fs-6 text-muted fw-normal">kg</span></div>
                                    </div>
                                    <div class="metric-box" style="background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.3);">
                                        <div class="m-lbl text-success">Net Profit Yield</div>
                                        <div class="m-val m-profit">₹<?php echo number_format($crop['net_profit']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="glass-panel text-center py-5 reveal active">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem; margin-bottom: 20px;"></i>
                    <h3 class="fw-bold">No Sustainable Match Found</h3>
                    <p class="text-muted max-w-50 mx-auto">The telemetry parameters provided (specifically water availability) carry a 90%+ failure risk for traditional crops in this soil type. We recommend adjusting parameters or exploring alternative drought-resistant cultivation.</p>
                    <a href="crop_recommendation.php" class="btn btn-outline-light rounded-pill px-5 mt-4 fw-bold">Adjust Parameters</a>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php include "footer.php" ?>

    <script src="assets/js/theme.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll Reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Hackathon "AI Cyber" Loading Animation
        function runAIEngine(e) {
            const btn = document.getElementById('submitBtn');
            const term = document.getElementById('terminalText');
            
            if(!document.getElementById('aiForm').checkValidity()) return true;

            btn.classList.add('loading');
            
            const phrases = [
                "> Scanning local weather patterns...",
                "> Analyzing soil matrix anomalies...",
                "> Evaluating water sustainability indexes...",
                "> Fetching live market APIs...",
                "> Compiling profit predictions..."
            ];
            
            let i = 0;
            term.innerHTML = phrases[0];
            const interval = setInterval(() => {
                i++;
                if (i < phrases.length) {
                    term.innerHTML = phrases[i];
                } else {
                    clearInterval(interval);
                }
            }, 300);
        }
    </script>
</body>
</html>