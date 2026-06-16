<?php
require_once 'db.php';

$recommendations = [];
$is_submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Adding a tiny artificial delay to make the "AI Processing" animation visible (Optional, feels more premium)
  usleep(500000); // 0.5 seconds delay

  $is_submitted = true;
  $user_soil = $_POST['soil_type'];
  $user_season = $_POST['season'];
  $user_water = $_POST['water'];
  $user_land_size = (float) $_POST['land_size'];

  $stmt = $conn->prepare("SELECT * FROM crop_knowledge WHERE soil_type = ? AND season = ? AND water_req = ?");
  $stmt->bind_param("sss", $user_soil, $user_season, $user_water);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($crop = $result->fetch_assoc()) {
    $total_yield = $crop['yield_per_acre'] * $user_land_size;
    $total_revenue = $total_yield * $crop['price_per_kg'];
    $total_cost = $crop['cost_per_acre'] * $user_land_size;
    $net_profit = $total_revenue - $total_cost;

    $roi = ($total_cost > 0) ? ($net_profit / $total_cost) * 100 : 0;

    $crop['calculated_yield'] = $total_yield;
    $crop['total_revenue'] = $total_revenue;
    $crop['total_cost'] = $total_cost;
    $crop['net_profit'] = $net_profit;
    $crop['roi'] = round($roi, 2);

    $recommendations[] = $crop;
  }

  usort($recommendations, function ($a, $b) {
    return $b['net_profit'] <=> $a['net_profit'];
  });
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
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    /* =========================================
       DYNAMIC THEME VARIABLES
       ========================================= */
    :root {
      --bg-main: #f8fafc;
      --bg-card: #ffffff;
      --bg-input: #f1f5f9;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --border-color: #e2e8f0;
      --primary: #10b981;
      --primary-dark: #059669;
      --primary-glow: rgba(16, 185, 129, 0.3);
      --card-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
      --stat-bg: rgba(241, 245, 249, 0.8);
      --glass-bg: rgba(255, 255, 255, 0.85);
    }

    [data-theme="dark"] {
      --bg-main: #020617;
      --bg-card: #0f172a;
      --bg-input: #1e293b;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --border-color: rgba(255, 255, 255, 0.1);
      --primary: #10b981;
      --primary-dark: #34d399;
      --primary-glow: rgba(16, 185, 129, 0.15);
      --card-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
      --stat-bg: rgba(30, 41, 59, 0.6);
      --glass-bg: rgba(15, 23, 42, 0.75);
    }

    body {
      background-color: var(--bg-main);
      color: var(--text-main);
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      transition: background-color 0.4s ease, color 0.4s ease;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* =========================================
       ANIMATIONS
       ========================================= */
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(30px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes bgGlow {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }
    @keyframes iconFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .fade-in-up { animation: fadeInUp 0.7s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    /* =========================================
       HERO SECTION
       ========================================= */
    .ai-hero {
      position: relative;
      padding: 120px 0 60px;
      margin-bottom: -40px;
      text-align: center;
      z-index: 1;
    }

    .ai-hero::before {
      content: '';
      position: absolute;
      top: 0; left: 50%; transform: translateX(-50%);
      width: 100vw; height: 100%;
      background: radial-gradient(circle at center, var(--primary-glow) 0%, transparent 70%);
      z-index: -1;
      pointer-events: none;
    }

    .ai-badge {
      display: inline-block;
      padding: 8px 20px;
      background: rgba(16, 185, 129, 0.1);
      border: 1px solid rgba(16, 185, 129, 0.3);
      color: var(--primary);
      border-radius: 50px;
      font-weight: 700;
      font-size: 0.85rem;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    /* =========================================
       GLASS FORM PANEL
       ========================================= */
    .glass-panel {
      background: var(--glass-bg);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--border-color);
      border-radius: 28px;
      box-shadow: var(--card-shadow);
      padding: 40px;
      position: relative;
      z-index: 5;
    }

    .form-label {
      font-weight: 600;
      color: var(--text-main);
      font-size: 0.95rem;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-label i { color: var(--primary); }

    .form-select, .form-control {
      background-color: var(--bg-input);
      border: 2px solid transparent;
      color: var(--text-main);
      border-radius: 16px;
      padding: 14px 20px;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    .form-select:focus, .form-control:focus {
      background-color: var(--bg-card);
      border-color: var(--primary);
      box-shadow: 0 0 0 4px var(--primary-glow);
      color: var(--text-main);
    }

    /* Submit Button */
    .btn-ai-submit {
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      background-size: 200% 200%;
      color: white;
      border: none;
      padding: 18px;
      border-radius: 16px;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800;
      font-size: 1.1rem;
      letter-spacing: 0.5px;
      width: 100%;
      cursor: pointer;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      animation: bgGlow 4s ease infinite;
    }

    .btn-ai-submit:hover {
      transform: translateY(-4px);
      box-shadow: 0 15px 30px var(--primary-glow);
      color: white;
    }

    .btn-ai-submit .spinner-border { display: none; width: 1.5rem; height: 1.5rem; border-width: 3px; }
    .btn-ai-submit.loading .spinner-border { display: inline-block; }
    .btn-ai-submit.loading .btn-text { display: none; }
    .btn-ai-submit.loading { pointer-events: none; opacity: 0.9; }

    /* =========================================
       RESULT CARDS
       ========================================= */
    .results-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 30px;
    }

    .result-card {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 35px;
      margin-bottom: 25px;
      box-shadow: var(--card-shadow);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      overflow: hidden;
    }

    .result-card:hover {
      transform: translateY(-8px) scale(1.01);
      border-color: var(--primary);
      box-shadow: 0 25px 50px var(--primary-glow);
    }

    /* Best Match Highlighting */
    .best-match {
      border: 2px solid var(--primary);
    }

    .best-match::before {
      content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
      background: radial-gradient(circle at top right, var(--primary-glow), transparent 40%);
      pointer-events: none;
    }

    .ribbon-glow {
      position: absolute; top: 25px; right: -35px;
      background: linear-gradient(90deg, #f59e0b, #fbbf24);
      color: #000; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 0.8rem;
      padding: 8px 40px; transform: rotate(45deg); box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
      letter-spacing: 1px; z-index: 10;
    }

    .crop-icon-wrapper {
      font-size: 5.5rem; line-height: 1;
      filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));
      animation: iconFloat 6s ease-in-out infinite;
    }

    .insight-badge {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--bg-input); border: 1px solid var(--border-color);
      color: var(--text-muted); font-size: 0.85rem; font-weight: 600;
      padding: 6px 16px; border-radius: 50px; margin-top: 15px;
    }

    /* Metric Grid */
    .metric-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 15px; margin-top: 25px;
    }

    .metric-box {
      background: var(--stat-bg); border: 1px solid var(--border-color);
      padding: 20px; border-radius: 18px; text-align: center;
      transition: all 0.3s ease;
    }

    .metric-box:hover { background: var(--bg-card); border-color: var(--primary); }

    .metric-box.profit-box {
      background: rgba(16, 185, 129, 0.05); border-color: rgba(16, 185, 129, 0.2);
    }
    [data-theme="dark"] .metric-box.profit-box { background: rgba(16, 185, 129, 0.1); }

    .metric-lbl { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .metric-val { font-size: 1.4rem; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; color: var(--text-main); }
    .metric-val.profit-val { color: var(--primary); }

    /* ROI Progress */
    .roi-container { margin-top: 25px; padding-top: 20px; border-top: 1px dashed var(--border-color); }
    .roi-bar { height: 8px; border-radius: 10px; background: var(--border-color); margin-top: 10px; overflow: hidden; }
    .roi-fill { height: 100%; background: linear-gradient(90deg, var(--primary), #34d399); width: 0%; transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1); }

    .action-btn {
      background: var(--bg-card); border: 1px solid var(--border-color); color: var(--text-main);
      padding: 10px 24px; border-radius: 50px; font-weight: 600; transition: 0.3s; text-decoration: none;
    }
    .action-btn:hover { background: var(--primary); color: #fff; border-color: var(--primary); }

    @media (max-width: 768px) {
      .ai-hero { padding: 100px 0 40px; }
      .glass-panel { padding: 25px; }
      .result-card { padding: 25px; text-align: center; }
      .metric-grid { grid-template-columns: repeat(2, 1fr); }
      .crop-icon-wrapper { margin-bottom: 20px; }
      .ribbon-glow { right: -45px; top: 30px; font-size: 0.7rem; }
    }

    @media print {
      .no-print { display: none !important; }
      .result-card { border: 1px solid #000; box-shadow: none; break-inside: avoid; }
      body { background: #fff; color: #000; }
    }
  </style>
</head>

<body>
  <?php include "nav.php" ?>

  <div class="ai-hero no-print">
    <div class="container fade-in-up">
      <span class="ai-badge"><i class="fas fa-brain"></i> HarvestIQ Intelligence</span>
      <h1 class="fw-bold display-4 mb-3">Smart Crop Engine</h1>
      <p class="lead opacity-75 mx-auto" style="max-width: 600px;">Harness predictive algorithms to determine the most profitable crops tailored to your exact micro-environment.</p>
    </div>
  </div>

  <div class="container pb-5">

    <?php if (!$is_submitted): ?>
      <!-- INPUT FORM -->
      <div class="row justify-content-center fade-in-up delay-1">
        <div class="col-lg-8">
          <div class="glass-panel">
            <h4 class="mb-4 fw-bold text-center"><i class="fas fa-sliders-h text-primary"></i> Farm Parameters</h4>
            <form method="POST" id="aiForm" onsubmit="showLoading()">
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label"><i class="fas fa-layer-group"></i> Soil Type</label>
                  <select class="form-select" name="soil_type" required>
                    <option value="" disabled selected>Analyze soil matrix...</option>
                    <option value="Loamy">Loamy (Optimal balance)</option>
                    <option value="Clay">Clay (High retention)</option>
                    <option value="Sandy">Sandy (Rapid drainage)</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fas fa-cloud-sun"></i> Growing Season</label>
                  <select class="form-select" name="season" required>
                    <option value="" disabled selected>Select cycle...</option>
                    <option value="Rabi">Rabi (Winter / Dry)</option>
                    <option value="Kharif">Kharif (Monsoon / Wet)</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fas fa-water"></i> Water Availability</label>
                  <select class="form-select" name="water" required>
                    <option value="" disabled selected>Determine volume...</option>
                    <option value="Low">Low (Rainfed reliance)</option>
                    <option value="Medium">Medium (Partial irrigation)</option>
                    <option value="High">High (Unrestricted access)</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label"><i class="fas fa-vector-square"></i> Cultivation Area (Acres)</label>
                  <input type="number" step="0.1" min="0.1" class="form-control" name="land_size" placeholder="e.g. 2.5" required>
                </div>
              </div>
              <div class="mt-5">
                <button type="submit" class="btn-ai-submit" id="submitBtn">
                  <span class="btn-text"><i class="fas fa-bolt me-2"></i> Initialize Analysis</span>
                  <div class="spinner-border text-light" role="status"></div>
                  <span class="spinner-border-text ms-2 text-white" style="display:none; font-weight:700;">Processing Telemetry...</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

    <?php else: ?>
      <!-- RESULTS DASHBOARD -->
      <div class="results-header no-print fade-in-up">
        <div>
          <h2 class="fw-bold mb-1"><i class="fas fa-chart-pie text-primary me-2"></i> Projection Report</h2>
          <p class="text-muted mb-0">Analyzed for <?php echo htmlspecialchars($user_land_size); ?> acres • <?php echo htmlspecialchars($user_soil); ?> Soil</p>
        </div>
        <div class="d-flex gap-2">
          <button onclick="window.print()" class="action-btn"><i class="fas fa-print"></i> Export</button>
          <a href="crop_recommendation.php" class="action-btn" style="background: var(--primary); color: white; border-color: var(--primary);"><i class="fas fa-redo"></i> Recalibrate</a>
        </div>
      </div>

      <?php if (count($recommendations) > 0): ?>

        <?php foreach ($recommendations as $index => $crop): ?>
          <div class="result-card fade-in-up delay-<?php echo ($index % 3) + 1; ?> <?php echo ($index == 0) ? 'best-match' : ''; ?>">
            <?php if ($index == 0): ?><div class="ribbon-glow">AI TOP PICK</div><?php endif; ?>

            <div class="row align-items-center">
              <div class="col-lg-3 text-center border-lg-end mb-4 mb-lg-0" style="border-color: var(--border-color) !important;">
                <div class="crop-icon-wrapper"><?php echo $crop['icon']; ?></div>
                <h3 class="fw-bold mt-3 mb-2"><?php echo htmlspecialchars($crop['name']); ?></h3>
                <div class="insight-badge"><i class="far fa-clock text-primary"></i> <?php echo htmlspecialchars($crop['duration']); ?></div>
              </div>

              <div class="col-lg-9 ps-lg-5">
                <h5 class="fw-bold"><i class="fas fa-lightbulb text-warning me-2"></i> Agronomy Insights</h5>
                <p class="text-muted" style="line-height: 1.6;"><?php echo htmlspecialchars($crop['reason']); ?></p>

                <div class="metric-grid">
                  <div class="metric-box">
                    <div class="metric-lbl">Est. Capital</div>
                    <div class="metric-val text-danger">₹<?php echo number_format($crop['total_cost']); ?></div>
                  </div>
                  <div class="metric-box">
                    <div class="metric-lbl">Gross Revenue</div>
                    <div class="metric-val text-info">₹<?php echo number_format($crop['total_revenue']); ?></div>
                  </div>
                  <div class="metric-box">
                    <div class="metric-lbl">Volume Yield</div>
                    <div class="metric-val"><?php echo number_format($crop['calculated_yield']); ?> <span class="fs-6 text-muted fw-normal">kg</span></div>
                  </div>
                  <div class="metric-box profit-box">
                    <div class="metric-lbl text-success">Net Profit</div>
                    <div class="metric-val profit-val">₹<?php echo number_format($crop['net_profit']); ?></div>
                  </div>
                </div>

                <div class="roi-container">
                  <div class="d-flex justify-content-between align-items-end">
                    <span class="fw-bold text-muted" style="font-size: 0.85rem; text-transform: uppercase;">Return on Investment (ROI)</span>
                    <span class="fw-bold text-success fs-4"><?php echo $crop['roi']; ?>%</span>
                  </div>
                  <div class="roi-bar">
                    <div class="roi-fill" data-target-width="<?php echo min($crop['roi'], 100); ?>%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>

      <?php else: ?>
        <div class="glass-panel text-center py-5 fade-in-up delay-1">
          <div style="font-size: 5rem; color: var(--border-color); margin-bottom: 20px;"><i class="fas fa-search-minus"></i></div>
          <h3 class="fw-bold">No Optimum Match Derived</h3>
          <p class="text-muted max-w-50 mx-auto">The telemetry parameters provided do not align with our current highly-profitable crop matrices. Consider adjusting water availability or seasonal parameters.</p>
          <a href="crop_recommendation.php" class="btn-ai-submit d-inline-block w-auto mt-4 px-5 text-decoration-none">Adjust Parameters</a>
        </div>
      <?php endif; ?>

    <?php endif; ?>
  </div>

  <script src="assets/js/theme.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // 1. Submit Button Loading State
    function showLoading() {
      const btn = document.getElementById('submitBtn');
      btn.classList.add('loading');
      document.querySelector('.spinner-border-text').style.display = 'inline-block';
    }

    // 2. Animate ROI Progress Bars
    document.addEventListener("DOMContentLoaded", function() {
      setTimeout(() => {
        const progressBars = document.querySelectorAll('.roi-fill');
        progressBars.forEach(bar => {
          const targetWidth = bar.getAttribute('data-target-width');
          bar.style.width = targetWidth;
        });
      }, 150); // Delay ensures transition triggers after DOM paint
    });
  </script>
   <?php include "footer.php" ?>
</body>

</html>