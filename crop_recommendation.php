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
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Crop Advisor | HarvestIQ</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=7.0">

    <style>
        /* =========================================
           ULTRA-PREMIUM DASHBOARD SYNC THEME
           ========================================= */
        :root {
            --hiq-bg: #0B1120;
            --hiq-surface: rgba(30, 41, 59, 0.4);
            --hiq-surface-hover: rgba(30, 41, 59, 0.7);
            --hiq-border: rgba(255, 255, 255, 0.08);
            --hiq-text-main: #f8fafc;
            --hiq-text-soft: #94a3b8;
            --hiq-accent: #10b981;
            --hiq-accent-glow: rgba(16, 185, 129, 0.15);
            --hiq-input-bg: rgba(15, 23, 42, 0.6);
        }

        [data-theme="light"] {
            --hiq-bg: #f8fafc;
            --hiq-surface: rgba(255, 255, 255, 0.9);
            --hiq-surface-hover: #ffffff;
            --hiq-border: rgba(0, 0, 0, 0.08);
            --hiq-text-main: #0f172a;
            --hiq-text-soft: #64748b;
            --hiq-input-bg: #ffffff;
        }

        body {
            background-color: var(--hiq-bg);
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(16, 185, 129, 0.06), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.06), transparent 25%);
            color: var(--hiq-text-main);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        .text-muted { color: var(--hiq-text-soft) !important; }
        .text-main { color: var(--hiq-text-main) !important; }

        .reveal { opacity: 0; transform: translateY(30px); transition: 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* =========================================
           HEADER & INPUT FORM (GLASSMORPHISM)
           ========================================= */
        .engine-header { padding: 140px 20px 50px; text-align: center; }
        .engine-title { font-size: clamp(2.2rem, 5vw, 3.8rem); font-weight: 800; letter-spacing: -1px; margin-bottom: 12px; color: var(--hiq-text-main); }
        .engine-title span { color: var(--hiq-accent); }

        .glass-panel {
            background: var(--hiq-surface);
            backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--hiq-border);
            border-radius: 24px; padding: 40px; 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
        }

        .form-label { font-weight: 600; color: var(--hiq-text-soft); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;}
        .form-label i { color: var(--hiq-accent); font-size: 1.1rem;}

        .form-select, .form-control {
            background-color: var(--hiq-input-bg); border: 1px solid var(--hiq-border); color: var(--hiq-text-main);
            border-radius: 14px; padding: 14px 18px; font-weight: 500; font-size: 1rem; transition: 0.3s;
        }
        .form-select:focus, .form-control:focus {
            background-color: var(--hiq-surface); border-color: var(--hiq-accent); box-shadow: 0 0 0 4px rgba(16,185,129,0.15); color: var(--hiq-text-main);
        }
        
        [data-theme="dark"] .form-select option { background: #0B1120; color: #fff; }

        .btn-run-ai {
            background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; padding: 18px;
            border-radius: 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.1rem;
            width: 100%; cursor: pointer; transition: 0.3s; box-shadow: 0 8px 25px rgba(16,185,129,0.25);
        }
        .btn-run-ai:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(16,185,129,0.4); }
        
        .ai-terminal { display: none; text-align: center; }
        .btn-run-ai.loading .btn-content { display: none; }
        .btn-run-ai.loading .ai-terminal { display: block; font-family: 'Inter', monospace; font-size: 1rem; color: #fff; }

        /* =========================================
           RESULTS DASHBOARD CARDS
           ========================================= */
        .result-card {
            background: var(--hiq-surface); border: 1px solid var(--hiq-border); border-radius: 24px;
            padding: 30px; margin-bottom: 25px; transition: 0.4s; position: relative; overflow: hidden;
            backdrop-filter: blur(20px);
        }
        .result-card:hover { transform: translateY(-4px); background: var(--hiq-surface-hover); border-color: rgba(16,185,129,0.3); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        
        .best-match { border: 1px solid var(--hiq-accent); box-shadow: 0 0 30px rgba(16,185,129,0.1); }
        .best-match::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at top left, rgba(16,185,129,0.08), transparent 40%); pointer-events: none; }
        
        .ribbon { 
            position: absolute; top: 20px; right: -35px; background: var(--hiq-accent); color: #fff; 
            font-family: 'Plus Jakarta Sans'; font-weight: 800; font-size: 0.7rem; padding: 5px 40px; 
            transform: rotate(45deg); box-shadow: 0 4px 12px rgba(16,185,129,0.3); letter-spacing: 1px; z-index: 10;
        }

        .crop-header { display: flex; align-items: center; gap: 20px; margin-bottom: 25px; border-bottom: 1px solid var(--hiq-border); padding-bottom: 20px;}
        .crop-icon { font-size: 3.5rem; filter: drop-shadow(0 8px 15px rgba(16,185,129,0.2)); }
        .crop-title h3 { font-size: 1.8rem; font-weight: 800; margin: 0; color: var(--hiq-text-main);}
        .crop-duration { color: var(--hiq-accent); font-weight: 600; font-size: 0.9rem; margin-top: 4px; }

        /* Metrics Grid */
        .metrics-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); 
            gap: 15px; 
            margin-bottom: 20px;
        }
        .metric-box { 
            background: rgba(255,255,255,0.02); border: 1px solid var(--hiq-border); 
            padding: 18px 15px; border-radius: 16px; transition: 0.3s;
        }
        .metric-box:hover { background: rgba(16,185,129,0.04); border-color: rgba(16,185,129,0.2);}
        
        .m-lbl { font-size: 0.75rem; color: var(--hiq-text-soft); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;}
        .m-val { font-size: 1.4rem; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--hiq-text-main); word-break: break-word; line-height: 1.2;}
        .m-profit { color: var(--hiq-accent); }

        /* Advisory Insight Box */
        .insight-box { background: rgba(16,185,129,0.05); border-left: 3px solid var(--hiq-accent); padding: 18px 20px; border-radius: 0 12px 12px 0; margin-bottom: 25px;}
        .insight-box h6 { color: var(--hiq-text-main); font-weight: 700; margin-bottom: 8px; font-size: 1rem;}
        .insight-box p { color: var(--hiq-text-soft); font-size: 0.95rem; margin: 0; line-height: 1.6;}

        /* Circular Progress */
        .circular-chart { display: block; margin: 0 auto; max-width: 130px; max-height: 130px; }
        .circle-bg { fill: none; stroke: var(--hiq-border); stroke-width: 3.5; }
        .circle { fill: none; stroke-width: 2.8; stroke-linecap: round; animation: progress 1.5s ease-out forwards; }
        .percentage { fill: var(--hiq-text-main); font-family: 'Plus Jakarta Sans'; font-size: 0.5em; text-anchor: middle; font-weight: 800; }
        .score-lbl { fill: var(--hiq-text-soft); font-family: 'Inter'; font-size: 0.16em; text-anchor: middle; font-weight: 600; letter-spacing: 1px;}
        @keyframes progress { 0% { stroke-dasharray: 0 100; } }

        .risk-badge { padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1px solid;}
        .risk-success { background: rgba(16,185,129,0.1); color: var(--hiq-accent); border-color: rgba(16,185,129,0.2); }
        .risk-warning { background: rgba(245,158,11,0.1); color: #fbbf24; border-color: rgba(245,158,11,0.2); }
        .risk-danger { background: rgba(239,68,68,0.1); color: #ef4444; border-color: rgba(239,68,68,0.2); }
        .risk-info { background: rgba(59,130,246,0.1); color: #3b82f6; border-color: rgba(59,130,246,0.2); }

        .btn-outline-custom {
            border: 1px solid var(--hiq-border); color: var(--hiq-text-main); background: var(--hiq-surface);
            padding: 10px 24px; border-radius: 50px; font-weight: 600; text-decoration: none; transition: 0.3s; font-size: 0.9rem;
        }
        .btn-outline-custom:hover { background: var(--hiq-surface-hover); border-color: var(--hiq-accent); }
   
   
   /* =========================================
           🌟 DARK MODE TEXT VISIBILITY FIX 🌟
           ========================================= */
        [data-theme="dark"] .text-muted,
        [data-theme="dark"] p.text-muted,
        [data-theme="dark"] .form-label,
        [data-theme="dark"] .m-lbl,
        [data-theme="dark"] .guide-text {
            color: #94a3b8 !important; /* ডার্ক মোডের জন্য সফট লাইট-গ্রে কালার */
        }

        [data-theme="dark"] .text-main,
        [data-theme="dark"] h1, 
        [data-theme="dark"] h2, 
        [data-theme="dark"] h3,
        [data-theme="dark"] .engine-title,
        [data-theme="dark"] .guide-item-header {
            color: #f8fafc !important; /* মেইন টেক্সটের জন্য একদম পারফেক্ট সাদা */
        }
   </style>
</head>

<body>
    <?php include "nav.php" ?>

    <div class="engine-header reveal active">
        <div class="container">
            <h1 class="engine-title">Crop <span>Advisor</span></h1>
            <p class="lead text-muted mx-auto" style="max-width: 650px;">
                Tell us about your soil and season. We will help you choose the right crop to get a better harvest and profit.
            </p>
        </div>
    </div>

    <div class="container pb-5" style="max-width: 1100px;">
        <?php if (!$is_submitted): ?>
            <div class="row justify-content-center reveal active">
                <div class="col-lg-12">
                    <div class="glass-panel">
                        <form method="POST" id="aiForm" onsubmit="runAIEngine(event)">
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-seedling"></i> What is your soil type?</label>
                                    <select class="form-select" name="soil_type" required>
                                        <option value="" disabled selected>Choose your soil...</option>
                                        <option value="Loamy">Loamy (Fertile & Balanced)</option>
                                        <option value="Clay">Clay (Holds water well)</option>
                                        <option value="Sandy">Sandy (Drains water quickly)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-cloud-sun"></i> Current Season</label>
                                    <select class="form-select" name="season" required>
                                        <option value="" disabled selected>Select season...</option>
                                        <option value="Rabi">Rabi (Winter Season)</option>
                                        <option value="Kharif">Kharif (Monsoon Season)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-tint"></i> Water Access</label>
                                    <select class="form-select" name="water" required>
                                        <option value="" disabled selected>How is your water access?</option>
                                        <option value="Low">Low (Depends on rain)</option>
                                        <option value="Medium">Medium (Need support for water)</option>
                                        <option value="High">High (Plenty of water)</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-tractor"></i> Farm Size (Acres)</label>
                                    <input type="number" step="0.1" min="0.1" class="form-control" name="land_size" placeholder="e.g. 2.5" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-run-ai" id="submitBtn">
                                <span class="btn-content"><i class="fas fa-search me-2"></i> Find Best Crop</span>
                                <div class="ai-terminal" id="terminalText">Checking soil data...</div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 reveal active gap-3">
                <div>
                    <h2 class="fw-bold mb-1 text-main">Analysis Complete</h2>
                    <p class="text-muted m-0"><i class="fas fa-check-circle text-success me-2"></i> Recommendations for <?php echo htmlspecialchars($user_land_size); ?> acres of <?php echo htmlspecialchars($user_soil); ?> soil.</p>
                </div>
                <a href="crop_recommendation.php" class="btn-outline-custom"><i class="fas fa-redo me-2"></i> New Search</a>
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
                            <div class="col-lg-3 col-md-4 text-center border-end border-secondary border-opacity-10 mb-4 mb-md-0 d-flex flex-column justify-content-center">
                                <svg viewBox="0 0 36 36" class="circular-chart">
                                    <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path class="circle" stroke="<?php echo $circle_color; ?>" stroke-dasharray="<?php echo $dash_val; ?>" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <text x="18" y="19" class="percentage"><?php echo $crop['match_score']; ?>%</text>
                                    <text x="18" y="25" class="score-lbl">BEST FIT</text>
                                </svg>
                            </div>

                            <div class="col-lg-9 col-md-8 ps-md-4">
                                <div class="crop-header flex-column flex-sm-row">
                                    <div class="crop-icon"><?php echo $crop['icon']; ?></div>
                                    <div class="crop-title">
                                        <h3><?php echo htmlspecialchars($crop['name']); ?></h3>
                                        <div class="crop-duration"><i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($crop['duration']); ?> Days Harvest Cycle</div>
                                    </div>
                                    <div class="ms-sm-auto text-sm-end mt-3 mt-sm-0">
                                        <div class="risk-badge risk-<?php echo $crop['risk_color']; ?> mb-2">
                                            <i class="fas fa-shield-alt"></i> Risk: <?php echo $crop['risk_level']; ?>
                                        </div>
                                        <br>
                                        <div class="risk-badge risk-info">
                                            <i class="fas fa-droplet"></i> <?php echo $crop['water_efficiency']; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="insight-box">
                                    <h6><i class="fas fa-leaf text-success me-2"></i> Why this crop?</h6>
                                    <p><?php echo htmlspecialchars($crop['reason']); ?></p>
                                </div>

                                <div class="metrics-grid">
                                    <div class="metric-box">
                                        <div class="m-lbl">Estimated Cost</div>
                                        <div class="m-val text-danger">₹<?php echo number_format($crop['total_cost']); ?></div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="m-lbl">Expected Sales</div>
                                        <div class="m-val text-info">₹<?php echo number_format($crop['total_revenue']); ?></div>
                                    </div>
                                    <div class="metric-box">
                                        <div class="m-lbl">Expected Yield</div>
                                        <div class="m-val"><?php echo number_format($crop['calculated_yield']); ?> <span class="fs-6 text-muted fw-normal">kg</span></div>
                                    </div>
                                    <div class="metric-box" style="background: rgba(16,185,129,0.05); border-color: rgba(16,185,129,0.2);">
                                        <div class="m-lbl text-success">Your Profit</div>
                                        <div class="m-val m-profit">₹<?php echo number_format($crop['net_profit']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="glass-panel text-center py-5 reveal active">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3.5rem; margin-bottom: 15px;"></i>
                    <h3 class="fw-bold text-main">No Sustainable Match Found</h3>
                    <p class="text-muted mx-auto" style="max-width: 600px;">The parameters provided (specifically water availability) carry a high failure risk for traditional crops in this soil type. We recommend adjusting parameters or exploring alternative drought-resistant cultivation.</p>
                    <a href="crop_recommendation.php" class="btn btn-outline-custom mt-3">Adjust Parameters</a>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php include "footer.php" ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Scroll Reveal Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('active');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Loading Terminal Animation
        function runAIEngine(e) {
            const btn = document.getElementById('submitBtn');
            const term = document.getElementById('terminalText');
            
            if(!document.getElementById('aiForm').checkValidity()) return true;

            btn.classList.add('loading');
            
            const phrases = [
                "> Checking local weather status...",
                "> Reading soil condition data...",
                "> Analyzing water requirements...",
                "> Fetching latest market prices...",
                "> Calculating harvest potential..."
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