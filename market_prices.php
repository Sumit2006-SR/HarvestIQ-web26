<?php
require 'db.php';

$query = "SELECT * FROM market_prices ORDER BY updated_at DESC";
$result = mysqli_query($conn, $query);

$market_data_json = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $market_data_json[] = $row;
    }
}
$json_encoded_data = json_encode($market_data_json);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Market Prices | HarvestIQ</title>
    <script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3.0">
    <script src="assets/js/theme.js" defer></script>
    <style>
        .network-status { display: inline-flex; align-items: center; gap: 8px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--hiq-neon); margin-bottom: 5px; }
        .live-clock { font-family: 'Plus Jakarta Sans', monospace; font-size: 1.3rem; font-weight: 700; color: var(--text); }
        .mandi-loc { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }
        .unit-text { font-size: 0.9rem; color: var(--text-muted); }
        .crop-icon-wrap { width: 48px; height: 48px; background: var(--surface-2); border: 1px solid var(--glass-border); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-right: 12px; }
        .card-top { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .card-footer { margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--glass-border); display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); }
        .d-flex { display: flex; align-items: flex-start; }
    </style>
</head>
<body>

<?php include 'nav.php'; ?>

<div class="market-dashboard">
    <div class="dashboard-header">
       <div class="header-title-box">
    <h1>Live Market Exchange</h1>
    <p>Real-time agricultural pricing fetched directly from verified wholesale markets.</p>
</div>
<div class="status-box">
    <div class="network-status" id="netStatus">
        <div class="pulse-dot"></div> <span id="netText">System Online</span>
    </div>
    <div class="live-clock" id="clock">00:00:00</div>
</div>
</div>

<div class="controls-wrapper">
    <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
         <input type="text" id="searchInput" placeholder="Search crops (e.g., Rice, Potato) or Market..." onkeyup="filterData()">
    </div>
        <button class="filter-chip active" onclick="setFilter('all', this)">All Assets</button>
        <button class="filter-chip" onclick="setFilter('up', this)"><i class="fa-solid fa-arrow-trend-up"></i> Profit</button>
        <button class="filter-chip" onclick="setFilter('down', this)"><i class="fa-solid fa-arrow-trend-down"></i> Loss</button>
    </div>

    <div class="price-grid" id="marketGrid">
        <?php foreach ($market_data_json as $data): ?>
            <?php
                $trendClass = 'trend-stable';
                $trendIcon = '<i class="fa-solid fa-minus"></i>';
                $trendFilter = 'stable';
                if ($data['trend'] == 'up') {
                    $trendClass = 'trend-up';
                    $trendIcon = '<i class="fa-solid fa-arrow-up"></i>';
                    $trendFilter = 'up';
                } elseif ($data['trend'] == 'down') {
                    $trendClass = 'trend-down';
                    $trendIcon = '<i class="fa-solid fa-arrow-down"></i>';
                    $trendFilter = 'down';
                }
            ?>
            <div class="market-glass-card crop-item" data-trend="<?php echo $trendFilter; ?>">
                <div class="card-top">
                    <div class="d-flex">
                        <div class="crop-icon-wrap"><?php echo htmlspecialchars($data['icon']); ?></div>
                        <div>
                            <div class="crop-title search-target"><?php echo htmlspecialchars($data['crop_name']); ?></div>
                            <div class="mandi-loc search-target"><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($data['mandi_name']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="price-display">
                    <span class="currency">₹</span>
                    <span class="price-val"><?php echo htmlspecialchars(number_format($data['price'])); ?></span>
                    <span class="unit-text">/ <?php echo htmlspecialchars($data['unit']); ?></span>
                </div>
                <div class="trend-badge <?php echo $trendClass; ?>">
                    <?php echo $trendIcon; ?> <?php echo htmlspecialchars($data['price_change'] ?: 'Stable'); ?>
                </div>
                <div class="card-footer">
                    <span><i class="fa-solid fa-rotate"></i> Sync</span>
                    <span style="font-weight:600;"><?php echo date('d M, h:i A', strtotime($data['updated_at'])); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        let seconds = now.getSeconds();
        let ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        document.getElementById('clock').innerText = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
    }
    setInterval(updateClock, 1000);
    updateClock();

    function updateNetworkStatus() {
        const statusBox = document.getElementById('netStatus');
        const statusText = document.getElementById('netText');
        if (navigator.onLine) {
            statusBox.style.color = 'var(--hiq-neon)';
            statusBox.querySelector('.pulse-dot').style.backgroundColor = 'var(--hiq-neon)';
            statusText.innerText = 'System Online';
            <?php if (!empty($market_data_json)): ?>
            localStorage.setItem('harvestiq_market_cache', <?php echo $json_encoded_data; ?>);
            <?php endif; ?>
        } else {
            statusBox.style.color = '#f59e0b';
            statusBox.querySelector('.pulse-dot').style.backgroundColor = '#f59e0b';
            statusText.innerText = 'Offline Mode (Cached Data)';
        }
    }
    window.addEventListener('online', updateNetworkStatus);
    window.addEventListener('offline', updateNetworkStatus);
    updateNetworkStatus();

    let currentFilter = 'all';

    function setFilter(filterType, btnElement) {
        currentFilter = filterType;
        document.querySelectorAll('.filter-chip').forEach(btn => btn.classList.remove('active'));
        btnElement.classList.add('active');
        filterData();
    }

    function filterData() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('crop-item');
        for (let i = 0; i < cards.length; i++) {
            let cardTrend = cards[i].getAttribute('data-trend');
            let targets = cards[i].getElementsByClassName('search-target');
            let matchText = false;
            for (let j = 0; j < targets.length; j++) {
                if (targets[j].innerText.toLowerCase().includes(input)) {
                    matchText = true;
                    break;
                }
            }
            let matchFilter = (currentFilter === 'all') || (currentFilter === cardTrend);
            cards[i].style.display = (matchText && matchFilter) ? 'block' : 'none';
        }
    }
</script>
</body>
</html>
