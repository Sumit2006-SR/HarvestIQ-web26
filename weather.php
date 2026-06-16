<?php
require 'db.php';

// Fetch ALL active alerts from the database
$alerts_query = "SELECT id, message, target_area, expires_at FROM weather_alerts WHERE is_active = 1 AND (expires_at > NOW() OR expires_at IS NULL) ORDER BY created_at DESC";
$alerts_result = mysqli_query($conn, $alerts_query);
$active_alerts = [];

if ($alerts_result) {
    while($row = mysqli_fetch_assoc($alerts_result)) {
        $active_alerts[] = $row;
    }
}
$alerts_json = json_encode($active_alerts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Precision Radar | HarvestIQ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  
  <style>
    /* ── DYNAMIC THEME VARIABLES ── */
    :root {
      --green: #10b981;
      --green-d: #059669;
      --bg-grad-1: #d4f5e9;
      --bg-grad-2: #bfdbfe;
      --bg-grad-3: #e0e7ff;
      --text-main: #0f172a;
      --text-muted: #475569;
      --glass-bg: rgba(255, 255, 255, 0.72);
      --glass-border: rgba(255, 255, 255, 0.8);
      --tile-bg: rgba(255, 255, 255, 0.85);
      --map-border: rgba(255, 255, 255, 0.9);
      --panel-bg: rgba(255, 255, 255, 0.88);
    }

    [data-theme="dark"] {
      --bg-grad-1: #020617;
      --bg-grad-2: #064e3b;
      --bg-grad-3: #0f172a;
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --glass-bg: rgba(15, 23, 42, 0.65);
      --glass-border: rgba(255, 255, 255, 0.08);
      --tile-bg: rgba(30, 41, 59, 0.7);
      --map-border: rgba(255, 255, 255, 0.1);
      --panel-bg: rgba(15, 23, 42, 0.85);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, var(--bg-grad-1) 0%, var(--bg-grad-2) 50%, var(--bg-grad-3) 100%);
      background-size: 300% 300%;
      animation: bgShift 20s ease infinite;
      min-height: 100vh;
      color: var(--text-main);
      overflow-x: hidden;
      transition: background 0.5s ease, color 0.5s ease;
    }

    @keyframes bgShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* ── PREMIUM MULTI-ALERT BANNER ── */
    #dynamicAlertsContainer {
        position: relative;
        z-index: 1000;
        padding-top: 110px; /* To clear the sticky nav */
        padding-bottom: 0;
    }

    .premium-alert-banner {
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.95), rgba(185, 28, 28, 0.95));
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(254, 202, 202, 0.4);
      border-radius: 20px;
      padding: 18px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 15px 35px rgba(239, 68, 68, 0.25), inset 0 0 20px rgba(255, 255, 255, 0.15);
      position: relative;
      overflow: hidden;
      animation: slideDownAlert 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .premium-alert-banner::before {
      content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
      animation: shine 4s infinite;
    }

    @keyframes shine { 100% { left: 200%; } }
    @keyframes slideDownAlert { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }

    .alert-content-wrapper { display: flex; align-items: center; gap: 20px; z-index: 2; position: relative;}

    .alert-pulse-icon {
      width: 50px; height: 50px;
      background: rgba(255, 255, 255, 0.25);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.5rem; color: #fff;
      box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.6);
      animation: alertPulse 1.5s infinite;
      flex-shrink: 0;
    }

    @keyframes alertPulse {
      70% { box-shadow: 0 0 0 15px rgba(255, 255, 255, 0); }
      100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }

    .alert-text-content { color: #fff; }
    .alert-text-content h6 { margin: 0 0 6px 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.95rem; font-weight: 800; letter-spacing: 1.5px; color: #fecaca; text-transform: uppercase; display: flex; align-items: center; gap: 8px;}
    .alert-text-content p { margin: 0; font-size: 1.1rem; font-weight: 600; line-height: 1.4; text-shadow: 0 2px 4px rgba(0,0,0,0.2);}

    .alert-dismiss-btn {
      background: rgba(0, 0, 0, 0.2); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%;
      width: 40px; height: 40px; color: #fff; cursor: pointer; transition: 0.3s;
      display: flex; align-items: center; justify-content: center; z-index: 2; position: relative;
    }
    .alert-dismiss-btn:hover { background: rgba(0, 0, 0, 0.4); transform: scale(1.1); }

    /* ── DYNAMIC SEARCH BOX ── */
    .location-search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-search-box {
        position: relative;
        width: 280px;
    }

    .custom-search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        z-index: 2;
    }

    .custom-search-input {
        width: 100%;
        background: var(--panel-bg);
        border: 2px solid var(--glass-border);
        border-radius: 14px;
        padding: 11px 18px 11px 40px;
        font-weight: 600;
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
        transition: all .3s ease;
    }

    .custom-search-input:focus {
        outline: none;
        border-color: var(--green);
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
    }

    .custom-search-input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .btn-search {
        background: var(--green);
        color: white;
        border: none;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-search:hover {
        background: var(--green-d);
        transform: translateY(-2px);
    }

    /* ── GLASS CARD ── */
    .glass-card {
      background: var(--glass-bg);
      backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
      border: 1.5px solid var(--glass-border);
      border-radius: 28px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.07);
      padding: 30px;
      transition: box-shadow .35s ease, transform .35s ease, background 0.4s ease;
    }

    /* ── MAP ── */
    .map-wrapper {
      position: relative; height: 100%; min-height: 520px;
      border-radius: 24px; overflow: hidden;
      border: 4px solid var(--map-border);
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
      transition: border-color 0.4s ease;
    }
    #map { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; background: var(--bg-grad-3);}

    .btn-gps {
      position: absolute; bottom: 20px; right: 20px; z-index: 400;
      background: linear-gradient(135deg, var(--green), var(--green-d));
      color: #fff; border-radius: 50px; font-weight: 700; padding: 12px 24px;
      border: none; box-shadow: 0 10px 28px rgba(16, 185, 129, 0.45);
      transition: all .3s ease; display: flex; align-items: center; gap: 9px; font-size: 0.97rem;
    }
    .btn-gps:hover { transform: translateY(-4px) scale(1.03); box-shadow: 0 16px 36px rgba(16, 185, 129, 0.6); }

    /* ── METRICS ── */
    .temp-display { font-size: 5.2rem; font-weight: 800; line-height: 1; letter-spacing: -4px; color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif;}
    .metric-tile { background: var(--tile-bg); border-radius: 18px; padding: 17px 12px; text-align: center; border: 1.5px solid var(--glass-border); transition: all .3s ease; }
    .metric-val { font-size: 1.3rem; font-weight: 800; color: var(--text-main); }
    .metric-lbl { font-size: 0.7rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: var(--text-muted); }

    .extra-row { background: var(--glass-bg); border-radius: 18px; padding: 16px 20px; display: flex; flex-wrap: wrap; gap: 12px; border: 1.5px solid var(--glass-border); }
    .extra-item { display: flex; align-items: center; gap: 10px; flex: 1 1 130px; }
    .extra-item .fw-bold { color: var(--text-main); }

    /* ── ADVISORY PANEL ── */
    .advisory-panel {
      border-radius: 24px; padding: 28px; border-left: 10px solid var(--green);
      background: var(--panel-bg); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06); transition: all .35s ease;
    }
    .advisory-panel h5 { color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif;}

    .score-orb {
      width: 85px; height: 85px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 1.4rem; color: #fff; box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18); position: relative;
    }
    .score-orb::after { content: ''; position: absolute; inset: -6px; border-radius: 50%; border: 2px dashed rgba(16, 185, 129, 0.5); animation: spinRing 10s linear infinite; }
    @keyframes spinRing { 100% { transform: rotate(360deg); } }

    .section-tag {
      background: var(--panel-bg); border-radius: 50px; padding: 6px 18px; font-weight: 700; font-size: 0.82rem;
      letter-spacing: 0.8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); display: inline-flex; align-items: center; gap: 7px; color: var(--text-main);
    }
    
    .animate-in { animation: fadeUp 0.7s ease forwards; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }

    /* ── LOADER ── */
    .loader-overlay { position: fixed; inset: 0; background: var(--bg-grad-3); z-index: 9999; display: flex; flex-direction: column; justify-content: center; align-items: center; transition: opacity 0.5s ease; }
    .pulse-logo { width: 80px; height: 80px; background: linear-gradient(135deg, var(--green), var(--green-d)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #fff; animation: pulseAnim 1.6s ease-in-out infinite; }
    @keyframes pulseAnim { 0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.55); } 70% { box-shadow: 0 0 0 24px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
  </style>
</head>

<body>
  <?php include 'nav.php' ?>

  <!-- LOADER -->
  <div class="loader-overlay" id="loader">
    <div class="pulse-logo"><i class="fa-solid fa-leaf"></i></div>
    <h4 class="mt-4 fw-bold text-success">Calibrating Radars…</h4>
    <p class="text-muted mt-1 fw-medium">Fetching hyper-local atmospheric data</p>
  </div>

  <!-- 🚨 STATIC ALERTS CONTAINER (Always right below Nav) 🚨 -->
  <div id="dynamicAlertsContainer" class="container-xl" style="display: none;">
      <!-- Alerts injected by JS -->
  </div>

  <div class="container-xl py-4 animate-in" id="mainWrapper" style="display:none; padding-top: 110px;">

    <!-- Header & DYNAMIC SEARCH -->
    <div class="row align-items-end mb-4 g-3">
      <div class="col-lg-7">
        <span class="section-tag mb-2">
          <i class="fa-solid fa-satellite text-success"></i> Live Atmospheric Feed
        </span>
        <h1 class="fw-bold display-5 mb-1" style="letter-spacing:-1.5px; font-family: 'Plus Jakarta Sans', sans-serif;">Precision Radar</h1>
        <p class="text-muted fs-5 mb-0 fw-medium">Search any village, district, or pin code to get real-time intelligence.</p>
      </div>
      <div class="col-lg-5 text-lg-end d-flex flex-column align-items-lg-end gap-2">
        
        <!-- Dynamic Location Search Form -->
        <div class="location-search-wrapper">
            <div class="custom-search-box">
                <i class="fa-solid fa-location-dot"></i>
                <input type="text" id="customLocationInput" class="custom-search-input" placeholder="e.g. Memari, Hooghly" onkeypress="handleEnter(event)">
            </div>
            <button class="btn-search" onclick="searchCustomLocation()">
                Search
            </button>
        </div>

        <span id="lastUpdated" style="font-size: 0.8rem; font-weight: 600; color: var(--text-muted);"></span>
      </div>
    </div>

    <!-- Row: Map + Weather Card -->
    <div class="row g-4 mb-4">

      <!-- MAP -->
      <div class="col-xl-5 col-lg-6">
        <div class="map-wrapper">
          <div id="map"></div>
          <button class="btn-gps" onclick="getLocation()">
            <i class="fa-solid fa-location-crosshairs"></i> Locate My Field
          </button>
        </div>
      </div>

      <!-- WEATHER DATA -->
      <div class="col-xl-7 col-lg-6 d-flex flex-column gap-4">

        <div class="glass-card">
          <div class="row align-items-center mb-4 g-3">
            <div class="col-sm-6 text-center text-sm-start">
              <div class="weather-icon-wrap bg-warning bg-opacity-10 mx-auto mx-sm-0 mb-3" id="iconWrap" style="width: 90px; height: 90px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.8rem;">
                <i id="weatherIcon" class="fa-solid fa-cloud-sun text-warning"></i>
              </div>
              <h2 id="condition" class="fw-bold text-capitalize mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif;">--</h2>
              <p class="text-muted fw-semibold fs-5 mb-0">
                <i class="fa-solid fa-map-pin text-danger me-1"></i> <span id="locationName">--</span>
              </p>
            </div>
            <div class="col-sm-6 d-flex justify-content-center justify-content-sm-end align-items-center gap-4">
              <div class="text-center">
                <div class="score-orb bg-success mx-auto" id="scoreCircle">--%</div>
                <small class="fw-bold text-muted mt-2 d-block text-uppercase" style="letter-spacing:1px;">Suitability</small>
              </div>
              <div class="text-center border-start ps-4 border-secondary border-opacity-25">
                <div class="temp-display"><span id="tempValue">--</span>°</div>
                <p class="text-muted fs-5 fw-bold mt-1 mb-0">Celsius</p>
              </div>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
              <div class="metric-tile">
                <div class="metric-icon bg-info bg-opacity-10"><i class="fa-solid fa-droplet text-info"></i></div>
                <div class="metric-val"><span id="humidityValue">--</span>%</div>
                <div class="metric-lbl">Humidity</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="metric-tile">
                <div class="metric-icon bg-secondary bg-opacity-10"><i class="fa-solid fa-wind text-secondary"></i></div>
                <div class="metric-val"><span id="windValue">--</span></div>
                <div class="metric-lbl">Wind km/h</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="metric-tile">
                <div class="metric-icon bg-success bg-opacity-10"><i class="fa-solid fa-gauge-high text-success"></i></div>
                <div class="metric-val"><span id="pressureValue">--</span></div>
                <div class="metric-lbl">Pressure hPa</div>
              </div>
            </div>
            <div class="col-6 col-md-3">
              <div class="metric-tile">
                <div class="metric-icon bg-warning bg-opacity-10"><i class="fa-solid fa-eye text-warning"></i></div>
                <div class="metric-val"><span id="visibilityValue">--</span></div>
                <div class="metric-lbl">Vis km</div>
              </div>
            </div>
          </div>

          <div class="extra-row">
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(251,146,60,.12); color:#f97316;"><i class="fa-solid fa-temperature-half"></i></div>
              <div><div class="fw-bold fs-6"><span id="feelsLike">--</span>°C</div><div class="metric-lbl">Feels Like</div></div>
            </div>
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(99,102,241,.1); color:#6366f1;"><i class="fa-solid fa-compass"></i></div>
              <div><div class="fw-bold fs-6"><span id="windDir">--</span></div><div class="metric-lbl">Wind Dir</div></div>
            </div>
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(59,130,246,.1); color:#3b82f6;"><i class="fa-solid fa-cloud-rain"></i></div>
              <div><div class="fw-bold fs-6"><span id="rainValue">0</span> mm</div><div class="metric-lbl">Rain (1hr)</div></div>
            </div>
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(148,163,184,.15); color:var(--text-muted);"><i class="fa-solid fa-cloud"></i></div>
              <div><div class="fw-bold fs-6"><span id="cloudsValue">--</span>%</div><div class="metric-lbl">Cloud Cover</div></div>
            </div>
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(250,204,21,.12); color:#eab308;"><i class="fa-solid fa-sun"></i></div>
              <div><div class="fw-bold fs-6"><span id="sunriseVal">--</span></div><div class="metric-lbl">Sunrise</div></div>
            </div>
            <div class="extra-item">
              <div class="ei-icon" style="background:rgba(239,68,68,.1); color:#ef4444;"><i class="fa-solid fa-moon"></i></div>
              <div><div class="fw-bold fs-6"><span id="sunsetVal">--</span></div><div class="metric-lbl">Sunset</div></div>
            </div>
          </div>
        </div>

        <div class="advisory-panel" id="advisoryBox">
          <div class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary border-opacity-25 pb-3">
            <div class="d-flex align-items-center gap-3">
              <div class="bg-success bg-opacity-10 p-3 rounded-circle" id="advisoryIconBg">
                <i class="fa-solid fa-microchip fs-3 text-success" id="advisoryIcon"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-0">AI Agronomist</h5>
                <small class="text-muted fw-semibold">Real-time crop intelligence</small>
              </div>
            </div>
            <span class="badge bg-success px-4 py-2 rounded-pill fs-6 shadow-sm" id="riskBadge">Risk: Low</span>
          </div>
          <p class="fs-5 fw-medium mb-0" id="advisoryText" style="line-height:1.85; color: var(--text-muted);">
            Awaiting satellite coordinates to generate custom agricultural insights…
          </p>
        </div>

      </div>
    </div>
  </div>

  <?php include "footer.php" ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    // --- 🚨 DISPLAY ALL ACTIVE ALERTS 🚨 ---
    const dbAlerts = <?php echo $alerts_json; ?>;

    function renderAllAlerts() {
        const alertsContainer = document.getElementById('dynamicAlertsContainer');
        const mainWrapper = document.getElementById('mainWrapper');

        if (dbAlerts && dbAlerts.length > 0) {
            let alertsHTML = '';
            
            dbAlerts.forEach((alert, index) => {
                let marginStyle = (index === dbAlerts.length - 1) ? 'margin-bottom: 30px;' : 'margin-bottom: 15px;';
                
                alertsHTML += `
                <div class="premium-alert-banner" id="alert-banner-${index}" style="${marginStyle}">
                    <div class="alert-content-wrapper">
                        <div class="alert-pulse-icon"><i class="fa-solid fa-tower-broadcast"></i></div>
                        <div class="alert-text-content">
                            <h6>🚨 Admin Emergency Broadcast <span class="badge bg-white text-danger ms-2" style="font-size: 0.75rem; padding: 4px 10px;">For: ${alert.target_area}</span></h6>
                            <p>${alert.message}</p>
                        </div>
                    </div>
                    <button class="alert-dismiss-btn" onclick="document.getElementById('alert-banner-${index}').style.display='none';"><i class="fa-solid fa-xmark"></i></button>
                </div>
                `;
            });
            
            alertsContainer.innerHTML = alertsHTML;
            alertsContainer.style.display = 'block';
            mainWrapper.style.paddingTop = '10px'; 
        } else {
            alertsContainer.innerHTML = '';
            alertsContainer.style.display = 'none';
            mainWrapper.style.paddingTop = '110px'; 
        }
    }

    // --- LEAFLET MAP LOGIC (Dark/Light Mode Compatible) ---
    let map = null, marker = null;
    let currentTileLayer = null;
    const lightTiles = 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';
    const darkTiles = 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png';

    function getTileUrl() {
        return document.documentElement.getAttribute('data-theme') === 'dark' ? darkTiles : lightTiles;
    }

    function updateMapTheme() {
        if (!map) return;
        if (currentTileLayer) map.removeLayer(currentTileLayer);
        currentTileLayer = L.tileLayer(getTileUrl(), { maxZoom: 19 }).addTo(map);
    }

    document.addEventListener('click', function(e) {
        if(e.target.closest('.hiq-theme-toggle')) {
            setTimeout(updateMapTheme, 100); 
        }
    });

    function initMap(lat, lon) {
      if (!map) {
        map = L.map('map', { zoomControl: false }).setView([lat, lon], 12);
        L.control.zoom({ position: 'topright' }).addTo(map);
        updateMapTheme();
        
        map.on('click', function(e) { fetchWeatherByCoords(e.latlng.lat, e.latlng.lng); });
      } else {
        map.flyTo([lat, lon], 13, { animate: true, duration: 1.8 });
      }

      if (marker) map.removeLayer(marker);
      const icon = L.divIcon({
        className: '',
        html: `<div style="width:24px;height:24px;border-radius:50%;background:#10b981;border:4px solid #fff;box-shadow:0 0 0 4px rgba(16,185,129,.4),0 0 20px rgba(16,185,129,.6);"></div>`,
        iconSize: [24, 24], iconAnchor: [12, 12]
      });
      marker = L.marker([lat, lon], { icon }).addTo(map);
      setTimeout(() => map.invalidateSize(), 100);
    }

    function getLocation() {
      if (navigator.geolocation) {
        showLoader();
        navigator.geolocation.getCurrentPosition(
          pos => fetchWeatherByCoords(pos.coords.latitude, pos.coords.longitude),
          err => { hideLoader(); fetchWeather('Kolkata'); }
        );
      } else {
        fetchWeather('Kolkata');
      }
    }

    // --- DYNAMIC SEARCH LOGIC ---
    function handleEnter(e) {
        if (e.key === 'Enter') {
            searchCustomLocation();
        }
    }

    function searchCustomLocation() {
        const input = document.getElementById('customLocationInput').value.trim();
        if (input !== '') {
            fetchWeather(input);
        }
    }

    function fetchWeatherByCoords(lat, lon) { processWeather(`api/weather_api.php?lat=${lat}&lon=${lon}`); }
    function fetchWeather(city) { processWeather(`api/weather_api.php?city=${encodeURIComponent(city)}`); }

    async function processWeather(url) {
      showLoader();
      try {
        const res = await fetch(url, { cache: 'no-store' });
        const data = await res.json();
        if (data.status === 'success') {
          applyWeatherData(data);
        } else {
            alert('Location not found. Please try another name.');
        }
      } catch (err) {
        console.error(err);
      } finally {
        hideLoader();
      }
    }

    function applyWeatherData(d) {
      document.getElementById('mainWrapper').style.display = 'block';
      initMap(d.lat, d.lon);

      document.getElementById('locationName').textContent = d.location;
      document.getElementById('tempValue').textContent = d.temperature;
      document.getElementById('humidityValue').textContent = d.humidity;
      document.getElementById('windValue').textContent = d.wind_speed;
      document.getElementById('pressureValue').textContent = d.pressure;
      document.getElementById('visibilityValue').textContent = d.visibility;
      document.getElementById('condition').textContent = d.weather_condition;
      document.getElementById('advisoryText').textContent = d.crop_advisory;
      document.getElementById('feelsLike').textContent = d.feels_like;
      document.getElementById('windDir').textContent = d.wind_direction;
      document.getElementById('rainValue').textContent = d.rain_1h ?? 0;
      document.getElementById('cloudsValue').textContent = d.clouds;
      document.getElementById('sunriseVal').textContent = d.sunrise;
      document.getElementById('sunsetVal').textContent = d.sunset;
      document.getElementById('lastUpdated').textContent = '⟳ Last Sync: ' + new Date().toLocaleTimeString();

      const orb = document.getElementById('scoreCircle');
      orb.textContent = d.suitability_score + '%';
      orb.className = 'score-orb mx-auto ' + (d.suitability_score >= 75 ? 'bg-success' : d.suitability_score >= 45 ? 'bg-warning text-dark' : 'bg-danger');

      // Always render all active alerts from admin
      renderAllAlerts();
      
      updateVisuals(d);
      
      // Update custom input field with the resolved location name
      document.getElementById('customLocationInput').value = d.location;
    }

    function updateVisuals(d) {
      const cond = d.main_condition.toLowerCase();
      const icon = document.getElementById('weatherIcon');
      const wrap = document.getElementById('iconWrap');

      icon.className = 'fa-solid ';
      if (cond.includes('thunderstorm')) { icon.classList.add('fa-cloud-bolt', 'text-dark'); wrap.className='bg-dark bg-opacity-10'; } 
      else if (cond.includes('rain')) { icon.classList.add('fa-cloud-showers-heavy', 'text-primary'); wrap.className='bg-primary bg-opacity-10'; } 
      else if (cond.includes('clear')) { icon.classList.add('fa-sun', 'text-warning'); wrap.className='bg-warning bg-opacity-10'; } 
      else if (cond.includes('cloud')) { icon.classList.add('fa-cloud', 'text-secondary'); wrap.className='bg-secondary bg-opacity-10'; } 
      else { icon.classList.add('fa-cloud-sun', 'text-warning'); wrap.className='bg-warning bg-opacity-10'; }

      const box = document.getElementById('advisoryBox');
      const aIcon = document.getElementById('advisoryIcon');
      const aBg = document.getElementById('advisoryIconBg');
      const badge = document.getElementById('riskBadge');

      badge.textContent = 'Risk: ' + d.risk_level;

      if (d.action_type === 'success') {
        box.style.borderLeftColor = '#10b981';
        aIcon.className = 'fa-solid fa-microchip fs-3 text-success';
        aBg.className = 'bg-success bg-opacity-10 p-3 rounded-circle';
        badge.className = 'badge bg-success px-4 py-2 rounded-pill fs-6 text-white border-0';
      } else if (d.action_type === 'warning') {
        box.style.borderLeftColor = '#f59e0b';
        aIcon.className = 'fa-solid fa-triangle-exclamation fs-3 text-warning';
        aBg.className = 'bg-warning bg-opacity-10 p-3 rounded-circle';
        badge.className = 'badge bg-warning text-dark px-4 py-2 rounded-pill fs-6 border-0';
      } else {
        box.style.borderLeftColor = '#ef4444';
        aIcon.className = 'fa-solid fa-shield-halved fs-3 text-danger';
        aBg.className = 'bg-danger bg-opacity-10 p-3 rounded-circle';
        badge.className = 'badge bg-danger px-4 py-2 rounded-pill fs-6 text-white border-0';
      }
    }

    function showLoader() { document.getElementById('loader').style.display = 'flex'; document.getElementById('loader').style.opacity = '1'; }
    function hideLoader() { setTimeout(() => { document.getElementById('loader').style.opacity = '0'; setTimeout(() => document.getElementById('loader').style.display = 'none', 500); }, 250); }

    // On Load
    window.onload = () => {
        fetchWeather('Kolkata'); // Loads default location on start
    };
  </script>
</body>
</html>