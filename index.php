<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HarvestIQ | Cultivate Smarter Decisions</title>
    
     <link rel="manifest" href="manifest.json">
    
    <script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
    
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    
     <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
     <link rel="stylesheet" href="style.css?v=3.0">
    <script src="assets/js/theme.js" defer></script>
</head>
<body>

    <div id="progressBar" class="progress-bar"></div>

    <?php include 'nav.php'; ?>

    <section class="hero" id="home">
        <div class="hero-bg-orbs">
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>
            <div class="hero-orb hero-orb-3"></div>
            <div class="hero-grid-lines"></div>
        </div>

        <div class="hero-float-cards">
            <div class="float-card float-card-1"><i class="fa-solid fa-cloud-sun-rain"></i> Live Weather</div>
            <div class="float-card float-card-2"><i class="fa-solid fa-chart-line"></i>Market Rates</div>
            <div class="float-card float-card-3"><i class="fa-solid fa-seedling"></i> Crop AI</div>
        </div>

        <div class="container hero-content reveal visible">
            <div class="hero-badge"><i class="fa-solid fa-microchip"></i> AI-Powered Agriculture</div>
            <h1>Grow More. Guess Less.<br><span class="gradient-text">Cultivate Smarter.</span></h1>
            <p>Empowering rural farmers with real-time weather intelligence, live market insights, and AI-driven crop recommendations to maximize yield and profit.</p>
            
            <div class="hero-buttons">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <button onclick="openAuthModal('signupModal')" class="btn-premium btn-primary-glow">
                        Join HarvestIQ Free <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <a href="#features" class="btn-premium btn-outline-glow">Explore Features</a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn-premium btn-primary-glow">
                        Enter Dashboard <i class="fa-solid fa-table-columns"></i>
                    </a>
                    <a href="market_prices.php" class="btn-premium btn-outline-glow">Live Market</a>
                <?php endif; ?>
            </div>

            <div class="hero-stats">
                <div class="stat-box">
                    <h3>24/7</h3>
                    <span>Live Weather Alerts</span>
                </div>
                <div class="stat-box">
                    <h3>100%</h3>
                    <span>Data Transparency</span>
                </div>
                <div class="stat-box">
                    <h3>Multi</h3>
                    <span>Language Support</span>
                </div>
            </div>
        </div>
    </section>

    <div class="market-ticker" id="market">
        <div class="ticker-content">
            <div class="ticker-item"><span class="t-crop">Wheat (Delhi):</span> <span class="t-price">₹2,250</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Rice (Kolkata):</span> <span class="t-price">₹3,100</span> <i class="fa-solid fa-caret-down t-down"></i></div>
            <div class="ticker-item"><span class="t-crop">Potato (UP):</span> <span class="t-price">₹1,800</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Mustard (Punjab):</span> <span class="t-price">₹5,400</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Maize (Bihar):</span> <span class="t-price">₹2,100</span> <i class="fa-solid fa-caret-down t-down"></i></div>
            <div class="ticker-item"><span class="t-crop">Wheat (Delhi):</span> <span class="t-price">₹2,250</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Rice (Kolkata):</span> <span class="t-price">₹3,100</span> <i class="fa-solid fa-caret-down t-down"></i></div>
            <div class="ticker-item"><span class="t-crop">Potato (UP):</span> <span class="t-price">₹1,800</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Mustard (Punjab):</span> <span class="t-price">₹5,400</span> <i class="fa-solid fa-caret-up t-up"></i></div>
            <div class="ticker-item"><span class="t-crop">Maize (Bihar):</span> <span class="t-price">₹2,100</span> <i class="fa-solid fa-caret-down t-down"></i></div>
        </div>
    </div>

    <section id="features">
        <div class="container">
            <div class="section-title reveal">
                <h2>Powerful Farming Intelligence</h2>
                <p>Everything you need to protect your crops and sell at the best price.</p>
            </div>
            <div class="features-grid">
                <div class="glass-card reveal">
                    <div class="card-icon"><i class="fa-solid fa-cloud-sun-rain"></i></div>
                    <h3>Live Weather Alerts</h3>
                    <p>Get hyper-local forecasts and early warnings for heavy rain or storms so you can plan your irrigation perfectly.</p>
                </div>
                <div class="glass-card reveal">
                    <div class="card-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3>Real-time Market Rates</h3>
                    <p>Track live crop prices across local markets to ensure middlemen never underpay you again.</p>
                </div>
                <div class="glass-card reveal">
                    <div class="card-icon"><i class="fa-solid fa-seedling"></i></div>
                    <h3>Smart Crop Guidance</h3>
                    <p>Input your soil type and season, and let our system recommend the most profitable crops to plant next.</p>
                </div>
                <div class="glass-card reveal">
                    <div class="card-icon"><i class="fa-solid fa-globe"></i></div>
                    <h3>Regional Languages</h3>
                    <p>Access the entire platform in your native language with zero technical complexities. Built for everyone.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="mission-section" id="mission">
        <div class="container">
            <div class="section-title reveal">
                <h2>Driven By Purpose</h2>
                <p>Technology rooted in the soil — built for the people who feed the nation.</p>
            </div>
            <div class="mission-grid">
                <div class="glass-card reveal">
                    <div class="card-icon" style="color: var(--hiq-amber); background: rgba(245, 158, 11, 0.12);"><i class="fa-solid fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To bridge the digital gap in agriculture by providing farmers with transparent, real-time, and actionable data.</p>
                </div>
                <div class="glass-card reveal">
                    <div class="card-icon"><i class="fa-solid fa-earth-asia"></i></div>
                    <h3>Our Vision</h3>
                    <p>A future where every farmer, regardless of their farm size, maximizes their yield and achieves financial freedom.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section reveal" id="contact">
        <h2>Ready to Transform Your Farm?</h2>
        <p>Join the growing community of smart farmers using HarvestIQ to cultivate better futures.</p>
        <?php if(!isset($_SESSION['user_id'])): ?>
            <button onclick="openAuthModal('signupModal')" class="btn-premium btn-primary-glow" style="font-size: 1.1rem; padding: 16px 40px;">
                Start Your Journey Now
            </button>
        <?php else: ?>
            <a href="dashboard.php" class="btn-premium btn-primary-glow" style="font-size: 1.1rem; padding: 16px 40px;">
                Go to Workspace
            </a>
        <?php endif; ?>
    </section>

    <?php include 'footer.php'; ?>
    <?php include 'login_signup.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const pb = document.getElementById('progressBar');
        window.addEventListener('scroll', () => {
            const d = document.documentElement;
            pb.style.width = (d.scrollTop / (d.scrollHeight - d.clientHeight)) * 100 + '%';
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('visible');
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>


<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js').then(registration => {
                console.log('ServiceWorker registered successfully.');
            }).catch(err => {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script>
</body>
</html>
