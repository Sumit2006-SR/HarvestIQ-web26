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
    <!-- Favicon for Browser Tab -->
<link rel="icon" type="image/png" href="assets/logo-192.png">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css?v=3.0">
    <script src="assets/js/theme.js" defer></script>

    <style>
        /* ==========================================
           HOW IT WORKS SECTION CSS 
        =========================================== */
        .hiw-section { padding: 100px 20px; position: relative; z-index: 1; overflow: hidden; }
        .hiw-header { text-align: center; margin-bottom: 70px; position: relative; z-index: 2; }
        .hiw-tag { display: inline-block; padding: 6px 16px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 50px; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 15px; }
        .hiw-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 15px; letter-spacing: -1px; }
        .hiw-subtitle { color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.6; }
        .hiw-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; max-width: 1200px; margin: 0 auto; position: relative; }

        @media(min-width: 992px) {
            .hiw-grid::before {
                content: ''; position: absolute; top: 45px; left: 15%; right: 15%; height: 2px;
                background: linear-gradient(90deg, rgba(16,185,129,0) 0%, rgba(16,185,129,0.5) 50%, rgba(16,185,129,0) 100%);
                z-index: -1; border-bottom: 2px dashed #10b981; opacity: 0.5; animation: dashFlow 20s linear infinite;
            }
        }
        @keyframes dashFlow { to { background-position: 1000px 0; } }

        .hiw-card {
            background: var(--glass-bg, rgba(15, 23, 42, 0.6));
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border, rgba(255, 255, 255, 0.05));
            border-radius: 24px; padding: 40px 30px; text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; z-index: 2;
        }
        .hiw-card:hover { transform: translateY(-15px); border-color: rgba(16, 185, 129, 0.4); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), inset 0 0 20px rgba(16, 185, 129, 0.05); }

        .hiw-step-node {
            width: 70px; height: 70px; background: var(--bg-main, #0f172a); border: 2px solid #10b981;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 800;
            color: #10b981; margin: -75px auto 25px auto;
            box-shadow: 0 0 20px rgba(16, 185, 129, 0.3), inset 0 0 10px rgba(16, 185, 129, 0.2); transition: 0.3s;
        }
        .hiw-card:hover .hiw-step-node { background: #10b981; color: #fff; box-shadow: 0 0 30px rgba(16, 185, 129, 0.6); transform: scale(1.1); }

        .hiw-icon {
            width: 80px; height: 80px; background: rgba(128, 128, 128, 0.05); border-radius: 20px;
            display: flex; align-items: center; justify-content: center; font-size: 2.2rem;
            color: var(--text-muted); margin: 0 auto 25px; transition: 0.3s; border: 1px solid rgba(128, 128, 128, 0.1);
        }
        .hiw-card:hover .hiw-icon { color: #10b981; background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
        .hiw-card h3 { color: var(--text-main); font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; margin-bottom: 15px; }
        .hiw-card p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin: 0; }
        .hiw-card::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 50%; height: 2px; background: #10b981; box-shadow: 0 -5px 20px rgba(16,185,129,0.5); opacity: 0; transition: 0.4s; }
        .hiw-card:hover::after { opacity: 1; width: 80%; }

        /* ==========================================
           TRUST BADGE SECTION (NEW) 
        =========================================== */
        .trust-section { padding: 40px 20px; background: rgba(16, 185, 129, 0.05); border-top: 1px solid rgba(16, 185, 129, 0.1); border-bottom: 1px solid rgba(16, 185, 129, 0.1); text-align: center; }
        .trust-content { display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 30px; max-width: 900px; margin: 0 auto; }
        .trust-item { display: flex; align-items: center; gap: 15px; }
        .trust-avatars { display: flex; }
        .trust-avatars img { width: 45px; height: 45px; border-radius: 50%; border: 3px solid var(--bg-main, #0f172a); margin-left: -15px; background: #fff;}
        .trust-avatars img:first-child { margin-left: 0; }
        .trust-text { text-align: left; }
        .trust-text h4 { margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--text-main); }
        .trust-text p { margin: 0; font-size: 0.9rem; color: var(--text-muted); font-weight: 500; }
        .trust-stars { color: #f59e0b; font-size: 1.1rem; }
    </style>
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
    <div class="float-card float-card-2"><i class="fa-solid fa-chart-line"></i> Market Rates</div>
    <div class="float-card float-card-3"><i class="fa-solid fa-seedling"></i> Crop Guide</div>
</div>

<div class="container hero-content reveal visible">
    <div class="hero-badge"><i class="fa-solid fa-lightbulb"></i> Smart Agriculture</div>
    <h1>Grow More. Guess Less.<br><span class="gradient-text">Cultivate Smarter.</span></h1>
    <p>Empowering rural farmers with real-time weather intelligence, live market insights, and data-driven crop recommendations to maximize yield and profit.</p>
<!-- </div> -->
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

    <!-- NEW TRUST BADGE SECTION -->
    <div class="trust-section reveal">
        <div class="trust-content">
            <div class="trust-item">
                <div class="trust-avatars">
                    <img src="https://i.pravatar.cc/100?img=11" alt="Farmer 1">
                    <img src="https://i.pravatar.cc/100?img=33" alt="Farmer 2">
                    <img src="https://i.pravatar.cc/100?img=68" alt="Farmer 3">
                </div>
                <div class="trust-text">
                    <div class="trust-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <p>Trusted by <strong>1,200+</strong> local farmers</p>
                </div>
            </div>
            <div style="width: 1px; height: 40px; background: rgba(128,128,128,0.2);" class="d-none d-md-block"></div>
            <div class="trust-item">
                <div class="trust-text" style="text-align: center;">
                    <h4><i class="fa-solid fa-shield-halved text-success me-2"></i> Verified Market Data</h4>
                    <p>Prices synced directly from wholesale Mandis</p>
                </div>
            </div>
        </div>
    </div>

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

    <!-- HOW IT WORKS SECTION -->
    <section class="hiw-section">
        <div class="hiw-header reveal">
            <span class="hiw-tag"><i class="fa-solid fa-route me-2"></i> User Journey</span>
            <h2 class="hiw-title">How HarvestIQ Empowers You</h2>
            <p class="hiw-subtitle">From registering your field to maximizing your profits—experience seamless agricultural intelligence in three simple steps.</p>
        </div>

        <div class="hiw-grid">
            <div class="hiw-card reveal" style="margin-top: 35px;">
                <div class="hiw-step-node">01</div>
                <div class="hiw-icon"><i class="fa-solid fa-user-check"></i></div>
                <h3>Set Your Profile</h3>
                <p>Sign up securely and input your region, soil type, and farming season. HarvestIQ creates a personalized dashboard instantly tailored to your exact needs.</p>
            </div>

            <div class="hiw-card reveal" style="transition-delay: 0.2s; margin-top: 35px;">
                <div class="hiw-step-node">02</div>
                <div class="hiw-icon"><i class="fa-solid fa-satellite-dish"></i></div>
                <h3>Sync Live Data</h3>
                <p>Our system fetches real-time atmospheric data via Precision Radar and syncs live commodity prices from your nearest wholesale Mandi, even working in offline mode.</p>
            </div>

            <div class="hiw-card reveal" style="transition-delay: 0.4s; margin-top: 35px;">
                <div class="hiw-step-node">03</div>
                <div class="hiw-icon"><i class="fa-solid fa-microchip"></i></div>
                <h3>Cultivate Smarter</h3>
                <p>Receive AI-driven advisories! Know exactly when to spray pesticides, when to irrigate, and when to sell your crops to bypass middlemen and maximize profit.</p>
            </div>
        </div>
    </section>
<!-- ==========================================
     PREMIUM PURPOSE & IMPACT SECTION
=========================================== -->
<section class="purpose-section" id="mission">
    <div class="container">
        <div class="section-title reveal">
            <h2>Driven By <span class="text-gradient-amber">Purpose</span></h2>
            <p>Technology rooted in the soil — built for the people who feed the nation.</p>
        </div>
        
        <div class="purpose-bento">
            
            <!-- Card 1: Mission (Amber Theme) -->
            <div class="purpose-card reveal">
                <div class="card-glow amber-glow"></div>
                <div class="card-icon" style="color: #fbbf24;"><i class="fa-solid fa-bullseye"></i></div>
                <h3>Our Mission</h3>
                <p>To completely eradicate the digital gap in agriculture by providing farmers with transparent, real-time, and actionable advisory data directly to their smartphones.</p>
                <ul class="purpose-list">
                    <li><i class="fa-solid fa-bolt text-warning"></i> Real-time API Sync</li>
                    <li><i class="fa-solid fa-language text-warning"></i> Zero Technical Barriers</li>
                </ul>
            </div>

            <!-- Card 2: Vision (Emerald Theme) -->
            <div class="purpose-card reveal" style="transition-delay: 0.2s;">
                <div class="card-glow emerald-glow"></div>
                <div class="card-icon" style="color: #34d399;"><i class="fa-solid fa-earth-asia"></i></div>
                <h3>Our Vision</h3>
                <p>A future where every farmer, regardless of their farm size or education, maximizes their yield and achieves absolute financial freedom.</p>
                <div class="floating-stat">
                    <span class="stat-num">100%</span>
                    <span class="stat-text">Farmer<br>Empowerment</span>
                </div>
            </div>

            <!-- Card 3: The Impact (Blue/Cyan Theme) -->
            <div class="purpose-card reveal" style="transition-delay: 0.4s;">
                <div class="card-glow blue-glow"></div>
                <div class="card-icon" style="color: #60a5fa;"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                <h3>The Impact</h3>
                <p>We bypass traditional supply chain bottlenecks, ensuring farmers get the exact Mandi rate they deserve while safeguarding crops from weather anomalies.</p>
                <div class="impact-tags">
                    <span class="i-tag"><i class="fa-solid fa-ban text-danger"></i> No Middlemen</span>
                    <span class="i-tag"><i class="fa-solid fa-arrow-trend-up text-success"></i> Maximum ROI</span>
                </div>
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