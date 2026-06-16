<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Assume db.php is needed, keeping require_once as per original
// require_once 'db.php'; // Uncomment if db.php is present in the directory
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HarvestIQ | Cultivate Smarter Decisions</title>
    
    <link rel="manifest" href="manifest.json">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Dependencies (Ensure you have these in your assets folder) -->
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    
    <!-- New Dark Emerald Glassmorphism Theme -->
    <link rel="stylesheet" href="style.css?v=5.0">
    <script src="assets/js/theme.js" defer></script>
</head>
<body class="bg-slate-900 text-slate-100 font-inter antialiased">

    <!-- Progress Bar -->
    <div id="progressBar" class="progress-bar"></div>

    <!-- Navigation Placeholder -->
    <?php // include 'nav.php'; ?>
    <nav class="glass-panel" style="position: fixed; top: 15px; left: 5%; right: 5%; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; z-index: 1000; border-radius: 100px;">
        <div class="font-jakarta fs-4 fw-bold text-white"><i class="fa-solid fa-leaf text-emerald-400"></i> HarvestIQ</div>
        <div class="d-none d-md-flex gap-4 fw-semibold">
            <a href="#features" class="text-white text-decoration-none">Features</a>
            <a href="#journey" class="text-white text-decoration-none">How it Works</a>
            <a href="#impact" class="text-white text-decoration-none">Our Impact</a>
        </div>
        <div>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <button class="btn-neon" style="padding: 10px 24px; font-size: 0.95rem;">Login / Sign Up</button>
            <?php else: ?>
                <button class="btn-neon" style="padding: 10px 24px; font-size: 0.95rem;">Dashboard</button>
            <?php endif; ?>
        </div>
    </nav>

    <!-- THE "WOW" HERO SECTION -->
    <section class="hero-wrapper">
        <div class="hero-bg-glow"></div>
        
        <!-- Floating UI Elements / Badges -->
        <div class="floating-badge badge-1">
            <i class="fa-solid fa-cloud-sun text-emerald-400"></i> <span>32°C Live Radar</span>
        </div>
        <div class="floating-badge badge-2">
            <i class="fa-solid fa-arrow-trend-up text-emerald-400"></i> <span>Wheat +4.2%</span>
        </div>
        <div class="floating-badge badge-3">
            <i class="fa-solid fa-robot text-emerald-400"></i> <span>AI Soil Analysis Ready</span>
        </div>

        <div class="container hero-content reveal">
            <!-- Sleek Badge -->
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill mb-4" style="background: rgba(52, 211, 153, 0.1); border: 1px solid rgba(52, 211, 153, 0.3); color: var(--emerald-400); font-weight: 700; font-size: 0.9rem;">
                <i class="fa-solid fa-microchip"></i> AI-Powered Agriculture v2.0
            </div>
            
            <h1 class="hero-title font-jakarta text-white">
                Grow More. Guess Less.<br>
                <span class="text-gradient">Cultivate Smarter.</span>
            </h1>
            <p class="hero-subtitle">
                The ultimate AI-powered agricultural advisory platform. We equip rural farmers with real-time weather intelligence, live market insights, and predictive crop recommendations to maximize yield and eliminate middleman exploitation.
            </p>
            
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-5">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <button onclick="openAuthModal('signupModal')" class="btn-neon">
                        Get Started Free <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <a href="#features" class="btn-glass">
                        <i class="fa-solid fa-play"></i> Watch Demo
                    </a>
                <?php else: ?>
                    <a href="dashboard.php" class="btn-neon">
                        Enter Workspace <i class="fa-solid fa-table-columns"></i>
                    </a>
                    <a href="market_prices.php" class="btn-glass">
                        Live Market Rates
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- THE "HACKATHON FLEX" TRUST BAR -->
    <div class="trust-bar reveal">
        <div class="trust-content">
            <div class="trust-item"><i class="fa-solid fa-bolt"></i> ⚡ AI-Powered Advisory</div>
            <div class="trust-item"><i class="fa-solid fa-wifi"></i> 📶 100% Offline Capable (PWA)</div>
            <div class="trust-item"><i class="fa-solid fa-language"></i> 🌍 Multi-lingual Native</div>
            <div class="trust-item"><i class="fa-solid fa-shield-check"></i> 🔒 Verified Mandi Data</div>
        </div>
    </div>

    <!-- MODERN BENTO-BOX FEATURE GRID -->
    <section id="features" class="bento-section">
        <div class="section-header reveal">
            <h2 class="section-title font-jakarta text-gradient">Intelligence at Scale</h2>
            <p class="text-secondary-light fs-5">Powerful tools engineered to revolutionize your farm's productivity.</p>
        </div>

        <div class="bento-grid">
            <!-- Large Card: Smart Crop Guidance -->
            <div class="glass-panel bento-item bento-large reveal">
                <div>
                    <div class="bento-icon"><i class="fa-solid fa-microchip"></i></div>
                    <h3 class="font-jakarta">Smart AI Crop Guidance</h3>
                    <p>Input your soil type, region, and season. Our proprietary machine learning models analyze historical yield data and current atmospheric conditions to recommend the most profitable crops to plant next, increasing your harvest success rate by up to 40%.</p>
                </div>
                <!-- Mockup UI Element inside card for visual flex -->
                <div class="mt-4 p-4 rounded-4 w-100" style="background: rgba(15,23,42,0.8); border: 1px solid rgba(255,255,255,0.05); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-light fw-bold fs-5">AI Recommendation: <span class="text-emerald-400">Golden Wheat</span></span>
                        <span class="badge bg-success bg-opacity-25 text-success border border-success p-2 fs-6">98% Match</span>
                    </div>
                    <div class="d-flex justify-content-between text-secondary-light mb-2 small fw-semibold">
                        <span>Analysis Processing</span>
                        <span>Complete</span>
                    </div>
                    <div class="progress" style="height: 8px; background: #1e293b; border-radius: 10px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-emerald-400" role="progressbar" style="width: 98%;"></div>
                    </div>
                </div>
            </div>

            <!-- Medium Card: Live Weather -->
            <div class="glass-panel bento-item bento-medium reveal" style="transition-delay: 0.1s;">
                <div class="bento-icon"><i class="fa-solid fa-cloud-bolt"></i></div>
                <h3 class="font-jakarta">Precision Weather Radar</h3>
                <p>Hyper-local forecasts and early warnings for heavy rain, storms, or droughts. Automate your irrigation perfectly.</p>
                
                <div class="mt-auto text-center p-4 rounded-4" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.1);">
                    <i class="fa-solid fa-cloud-sun fs-1 text-warning mb-3"></i>
                    <h2 class="text-white font-jakarta fw-bold mb-1" style="font-size: 3rem;">32°C</h2>
                    <span class="text-emerald-400 fw-bold"><i class="fa-solid fa-droplet"></i> 65% Humidity</span>
                </div>
            </div>

            <!-- Small Card 1: Market Rates -->
            <div class="glass-panel bento-item bento-small reveal" style="transition-delay: 0.2s;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bento-icon m-0" style="font-size: 1.8rem; padding: 12px;"><i class="fa-solid fa-chart-line"></i></div>
                    <span class="badge bg-danger bg-opacity-25 text-danger border border-danger">Live</span>
                </div>
                <h3 class="font-jakarta fs-4">Real-Time Market</h3>
                <p class="small mb-0">Track live prices across local Mandis. Never get underpaid again.</p>
            </div>

            <!-- Small Card 2: Language -->
            <div class="glass-panel bento-item bento-small reveal" style="transition-delay: 0.3s;">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="bento-icon m-0" style="font-size: 1.8rem; padding: 12px;"><i class="fa-solid fa-earth-asia"></i></div>
                    <span class="badge bg-primary bg-opacity-25 text-primary border border-primary">10+</span>
                </div>
                <h3 class="font-jakarta fs-4">Regional Ready</h3>
                <p class="small mb-0">Access the platform in your native language with zero friction.</p>
            </div>
        </div>
    </section>

    <!-- INTERACTIVE USER JOURNEY -->
    <section id="journey" class="journey-section">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="section-title font-jakarta text-white">How HarvestIQ Works</h2>
                <p class="text-secondary-light fs-5">From onboarding to harvest, experience seamless agricultural intelligence.</p>
            </div>

            <div class="timeline reveal">
                <div class="timeline-step">
                    <div class="step-circle"><i class="fa-solid fa-user-plus"></i></div>
                    <h4 class="font-jakarta">1. Set Profile</h4>
                    <p>Register securely. Input your region, soil type, and farming season. We instantly tailor the dashboard to your farm.</p>
                </div>
                
                <div class="timeline-step" style="transition-delay: 0.2s;">
                    <div class="step-circle"><i class="fa-solid fa-satellite-dish"></i></div>
                    <h4 class="font-jakarta">2. Sync Live Data</h4>
                    <p>Fetch real-time atmospheric data and live wholesale Mandi prices directly to your device, even in offline mode.</p>
                </div>

                <div class="timeline-step" style="transition-delay: 0.4s;">
                    <div class="step-circle"><i class="fa-solid fa-leaf"></i></div>
                    <h4 class="font-jakarta">3. Cultivate Smarter</h4>
                    <p>Receive predictive AI advisories on when to irrigate, spray pesticides, and sell to bypass middlemen.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- REAL-WORLD IMPACT SECTION -->
    <section id="impact" class="impact-section">
        <div class="impact-card reveal">
            <h2 class="font-jakarta text-gradient">Driven by Impact</h2>
            <p>We built HarvestIQ to empower the people who feed the nation. By providing transparent data and predictive analytics, we are leveling the playing field for rural farmers against exploitative middlemen.</p>
            
            <div class="impact-stats mt-5">
                <div class="impact-stat-item">
                    <h3 class="font-jakarta">1.2M+</h3>
                    <span>Farmers Reached</span>
                </div>
                <div class="impact-stat-item">
                    <h3 class="font-jakarta">28%</h3>
                    <span>Avg. Yield Increase</span>
                </div>
                <div class="impact-stat-item">
                    <h3 class="font-jakarta">₹45B</h3>
                    <span>Middleman Margins Saved</span>
                </div>
            </div>
            
            <div class="mt-5 pt-4">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <button onclick="openAuthModal('signupModal')" class="btn-neon" style="padding: 20px 50px; font-size: 1.2rem;">Join the Movement</button>
                <?php else: ?>
                    <a href="dashboard.php" class="btn-neon" style="padding: 20px 50px; font-size: 1.2rem;">Go to Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer Placeholder -->
    <?php // include 'footer.php'; ?>
    <?php // include 'login_signup.php'; ?>
    <footer class="text-center py-5 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
        <p class="text-secondary-light mb-0 fw-semibold">&copy; <?= date('Y') ?> HarvestIQ. Cultivate Smarter Decisions.</p>
    </footer>

    <!-- Bootstrap JS for functionalities (Assuming user relies on it) -->
    <!-- Make sure to keep the user's scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Animation and Progress Script -->
    <script>
        // Smooth Progress Bar logic
        const pb = document.getElementById('progressBar');
        if (pb) {
            window.addEventListener('scroll', () => {
                const d = document.documentElement;
                pb.style.width = (d.scrollTop / (d.scrollHeight - d.clientHeight)) * 100 + '%';
            });
        }

        // Advanced Scroll Reveal Animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // PWA Service Worker check
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js').catch(err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>
