<?php
/**
 * HarvestIQ — Premium Enterprise Footer
 * Glassmorphism, Dynamic OS Detection (PWA), and Live Ticker
 * Developed by Spark Devs
 */
?>
<style>
    /* ==========================================================================
       HARVESTIQ PREMIUM FOOTER SCOPE - DARK AGRITECH THEME
       ========================================================================== */
    .hiq-pro-footer-scope {
        --sz-ft-primary: #020617; /* Deep Slate/Black */
        --sz-ft-secondary: #0f172a;
        --sz-ft-accent: #10b981; /* Emerald Green */
        --sz-ft-accent-hover: #059669;
        --sz-ft-text-main: #f8fafc;
        --sz-ft-text-muted: #94a3b8;
        --sz-ft-border: rgba(255, 255, 255, 0.08);
        --sz-ft-bg: #020617; 
        font-family: 'Poppins', sans-serif;
        position: relative;
        z-index: 10;
        width: 100%;
        margin-top: 120px; 
    }

    .hiq-pro-footer-scope .pro-footer {
        background-color: var(--sz-ft-bg);
        border-top: 1px solid var(--sz-ft-border);
        padding: 0 0 40px 0;
        color: var(--sz-ft-text-muted);
        box-shadow: 0 -10px 40px rgba(0,0,0,0.2);
    }
/* 🌟 Floating Newsletter Box - Enterprise Glass Look */
    .hiq-pro-footer-scope .newsletter-box {
        /* নতুন Glassmorphism ব্যাকগ্রাউন্ড */
        background: rgba(15, 23, 42, 0.7); 
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.5), inset 0 0 0 1px rgba(16, 185, 129, 0.15);
        
        border-radius: 24px;
        padding: 45px 60px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 30px;
        margin-top: -75px; margin-bottom: 80px;
        position: relative; z-index: 20;
        overflow: hidden;
    }
    
    .hiq-pro-footer-scope .newsletter-box::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .hiq-pro-footer-scope .newsletter-text { flex: 1; min-width: 280px; position: relative; z-index: 2; }
    
    .hiq-pro-footer-scope .newsletter-text h4 {
        color: #ffffff; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;
        margin-bottom: 10px; font-size: 1.8rem; letter-spacing: -0.5px;
    }
    
    /* টেক্সটের রঙটা হালকা সবুজ থেকে গ্রে করে দিলাম যাতে গ্লাসের সাথে ভালো মানায় */
    .hiq-pro-footer-scope .newsletter-text p { color: #94a3b8; font-size: 1.05rem; margin: 0; font-weight: 500; }

    
    .hiq-pro-footer-scope .newsletter-form { display: flex; gap: 10px; flex: 1; min-width: 320px; max-width: 500px; position: relative; z-index: 2;}
    .hiq-pro-footer-scope .newsletter-form input {
        width: 100%; padding: 18px 30px; border-radius: 50px;
        border: 1px solid rgba(255,255,255,0.15); outline: none;
        font-size: 1rem; background: rgba(255, 255, 255, 0.05);
        color: #fff; backdrop-filter: blur(10px); transition: all 0.3s ease;
    }
    .hiq-pro-footer-scope .newsletter-form input::placeholder { color: rgba(255,255,255,0.5); }
    .hiq-pro-footer-scope .newsletter-form input:focus { background: rgba(255, 255, 255, 0.1); border-color: var(--sz-ft-accent); box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2); }
    
    .hiq-pro-footer-scope .btn-custom {
        position: absolute; right: 8px; top: 8px; bottom: 8px;
        background: var(--sz-ft-accent); color: #fff; border: none;
        border-radius: 50px; padding: 0 32px; font-weight: 700; font-size: 0.95rem;
        cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; gap: 10px;
    }
    .hiq-pro-footer-scope .btn-custom:hover { background: var(--sz-ft-accent-hover); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4); }

    /* 🌟 Ultra-Premium Footer Logo */
    .hiq-pro-footer-scope .sz-footer-brand {
        display: flex; align-items: center; gap: 12px; text-decoration: none;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 900;
        letter-spacing: -0.8px; color: #ffffff; transition: transform 0.4s ease; margin-bottom: 20px;
    }
    .hiq-pro-footer-scope .sz-footer-brand span { background: linear-gradient(135deg, #34d399 0%, #10b981 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    
    .hiq-pro-footer-scope .footer-icon-box {
        width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; 
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(4, 120, 87, 0.1));
        border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; transition: all 0.4s ease;
    }
    .hiq-pro-footer-scope .footer-brand-icon { font-size: 1.6rem; color: #34d399; transition: transform 0.4s ease; }
    
    .hiq-pro-footer-scope .sz-footer-brand:hover { transform: translateY(-2px); }
    .hiq-pro-footer-scope .sz-footer-brand:hover .footer-icon-box { border-color: rgba(16, 185, 129, 0.6); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.25); }
    .hiq-pro-footer-scope .sz-footer-brand:hover .footer-brand-icon { transform: scale(1.1) rotate(5deg); }
    
    .hiq-pro-footer-scope .footer-desc { font-size: 0.95rem; line-height: 1.8; margin-bottom: 30px; font-weight: 400; color: var(--sz-ft-text-muted); padding-right: 20px; }

    /* ==========================================================================
       2. ENTERPRISE DOWNLOAD SPLIT BUTTON (PWA)
       ========================================================================== */
    .hiq-pro-footer-scope .smart-install-box { position: relative; display: inline-block; width: 100%; max-width: 300px; }
    
    .hiq-pro-footer-scope .pro-download-group {
        display: flex; width: 100%;
        background: var(--sz-ft-secondary);
        border: 1px solid var(--sz-ft-border);
        border-radius: 14px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        z-index: 50;
    }
    .hiq-pro-footer-scope .pro-download-group:hover { box-shadow: 0 16px 32px rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); transform: translateY(-2px); }
    .hiq-pro-footer-scope .pwa-main-btn {
    flex: 1; display: flex; align-items: center; gap: 14px;
    background: transparent; color: #ffffff;
    padding: 14px 20px; border: none; 
    text-decoration: none; cursor: pointer; text-align: left;
    border-radius: 14px; /* Changed to full rounded corners */
    transition: 0.3s;
    width: 100%;
}
    .hiq-pro-footer-scope .pwa-main-btn:hover { background: rgba(255,255,255,0.03); }
    
    .hiq-pro-footer-scope .pwa-icon { font-size: 1.8rem; color: #34d399; transition: transform 0.3s; }
    .hiq-pro-footer-scope .pwa-main-btn:hover .pwa-icon { transform: scale(1.1); }
    .hiq-pro-footer-scope .pwa-text { display: flex; flex-direction: column; }
    .hiq-pro-footer-scope .pwa-text .os-name { font-weight: 700; font-size: 0.95rem; line-height: 1.2; font-family: 'Plus Jakarta Sans', sans-serif; color: white;}
    .hiq-pro-footer-scope .pwa-text .arch-name { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; font-weight: 600; color: #cbd5e1; margin-top: 2px;}
    
    .hiq-pro-footer-scope .pwa-dropdown-toggle {
        background: transparent; border: none; color: white;
        padding: 0 18px; border-radius: 0 14px 14px 0;
        cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center;
    }
    .hiq-pro-footer-scope .pwa-dropdown-toggle:hover { background: rgba(255,255,255,0.05); }
    
    .hiq-pro-footer-scope .download-dropdown-menu {
        position: absolute; bottom: calc(100% + 12px); left: 0; 
        width: 100%; min-width: 280px;
        background: #0f172a; backdrop-filter: blur(20px);
        border-radius: 14px; border: 1px solid rgba(255,255,255,0.1);
        box-shadow: 0 -15px 40px rgba(0, 0, 0, 0.4);
        padding: 10px 0; list-style: none; margin: 0; z-index: 100;
        opacity: 0; visibility: hidden; 
        transform: translateY(15px) scale(0.98);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .hiq-pro-footer-scope .download-dropdown-menu.active { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
    .hiq-pro-footer-scope .download-dropdown-menu li button {
        display: flex; align-items: center; gap: 14px; width: 100%;
        padding: 12px 24px; color: var(--sz-ft-text-muted); background: transparent; border: none;
        text-align: left; font-size: 0.9rem; font-weight: 500; transition: 0.2s; cursor: pointer;
    }
    .hiq-pro-footer-scope .download-dropdown-menu li button:hover { background: rgba(16,185,129,0.1); color: var(--sz-ft-accent); padding-left: 30px; font-weight: 600;}
    
    .hiq-pro-footer-scope .install-help-text { font-size: 0.8rem; color: var(--sz-ft-accent); font-weight: 500; display: none; margin-top: 12px; animation: fadeIn 0.3s; }

    /* 🌟 Multi-Column Links - Sleek Underline Sweep Animation */
    .hiq-pro-footer-scope .footer-links { padding-top: 10px; }
    .hiq-pro-footer-scope .footer-links h4 { 
        color: var(--sz-ft-text-main); font-family: 'Plus Jakarta Sans', sans-serif; 
        font-weight: 800; font-size: 1.1rem; margin-bottom: 25px; letter-spacing: -0.3px;
    }
    .hiq-pro-footer-scope .footer-links ul { list-style: none; padding: 0; margin: 0; }
    .hiq-pro-footer-scope .footer-links ul li { margin-bottom: 16px; }
    
    .hiq-pro-footer-scope .footer-links ul li a { 
        color: var(--sz-ft-text-muted); text-decoration: none; 
        font-size: 0.95rem; font-weight: 400; transition: color 0.3s; 
        position: relative; display: inline-block; padding-bottom: 2px;
    }
    
    /* 🪄 Magical Sweeping Underline */
    .hiq-pro-footer-scope .footer-links ul li a::after {
        content: ''; position: absolute; width: 0; height: 2px;
        bottom: 0; left: 0; background-color: var(--sz-ft-accent);
        transition: width 0.3s ease-in-out; border-radius: 2px;
    }
    .hiq-pro-footer-scope .footer-links ul li a:hover { color: #ffffff; }
    .hiq-pro-footer-scope .footer-links ul li a:hover::after { width: 100%; }

    /* 🌟 Contact Info & Premium Social Icons */
    .hiq-pro-footer-scope .contact-info li { display: flex; align-items: flex-start; gap: 14px; margin-bottom: 18px; font-size: 0.95rem; font-weight: 400; color: var(--sz-ft-text-muted); }
    .hiq-pro-footer-scope .contact-info li i { color: var(--sz-ft-accent); font-size: 1.2rem; margin-top: 3px; }
    .hiq-pro-footer-scope .contact-info li strong { display: block; color: var(--sz-ft-text-main); font-weight: 600; margin-bottom: 2px; }

    .hiq-pro-footer-scope .social-icons-pro { display: flex; gap: 12px; margin-top: 30px; }
    .hiq-pro-footer-scope .social-icons-pro a {
        width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.05); 
        color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem;
        transition: all 0.3s ease; text-decoration: none; position: relative; overflow: hidden; z-index: 1;
    }
    
    .hiq-pro-footer-scope .social-icons-pro a::before {
        content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: var(--sz-ft-accent); z-index: -1;
        transition: transform 0.3s ease; transform: scale(0); border-radius: 50%;
    }
    .hiq-pro-footer-scope .social-icons-pro a:hover::before { transform: scale(2); }
    .hiq-pro-footer-scope .social-icons-pro a:hover { color: #ffffff; border-color: transparent; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }

    /* 🌟 Footer Bottom & Dynamic Ticker */
    .hiq-pro-footer-scope .footer-bottom-pro { 
        margin-top: 60px; padding-top: 30px; 
        border-top: 1px solid var(--sz-ft-border); 
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; 
    }

    .footer-bottom-links { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .footer-bottom-links a {
        position: relative; color: var(--sz-ft-text-muted); font-size: 0.85rem; font-weight: 600;
        text-decoration: none; padding: 6px 12px; border-radius: 8px; background: transparent;
        transition: all 0.4s ease; display: inline-flex; align-items: center; z-index: 1;
    }
    .footer-bottom-links a::before {
        content: ''; width: 6px; height: 6px; background: var(--sz-ft-accent); border-radius: 50%;
        position: absolute; left: 12px; opacity: 0; transform: scale(0); transition: all 0.4s ease;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
    }
    .footer-bottom-links a::after {
        content: ''; position: absolute; inset: 0; background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; z-index: -1;
        opacity: 0; transform: translateY(10px) scale(0.95); transition: all 0.3s ease;
    }
    .footer-bottom-links a:hover { color: #ffffff; padding-left: 24px; transform: translateY(-2px); }
    .footer-bottom-links a:hover::before { opacity: 1; transform: scale(1); }
    .footer-bottom-links a:hover::after { opacity: 1; transform: translateY(0) scale(1); }

    .hiq-pro-footer-scope .ticker-wrap-pro {
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 50px;
        padding: 6px 18px; display: flex; align-items: center; gap: 14px;
        max-width: 420px; width: 100%; position: relative; overflow: hidden;
    }
    .hiq-pro-footer-scope .ticker-label-pro {
        background: #ef4444; color: white; font-size: 0.65rem; font-weight: 800;
        padding: 4px 12px; border-radius: 50px; animation: pulseRed 2s infinite; letter-spacing: 0.5px;
    }
    
    @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); } 70% { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
    
    .hiq-pro-footer-scope .ticker-content-box { flex: 1; overflow: hidden; position: relative; height: 22px; }
    .hiq-pro-footer-scope .ticker-text {
        font-size: 0.85rem; font-weight: 500; color: #cbd5e1;
        position: absolute; width: 100%; transition: top 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55), opacity 0.5s ease;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* Toast Notification */
    .sz-toast {
        position: fixed; bottom: -100px; left: 50%; transform: translateX(-50%);
        background: #0F172A; color: white; padding: 14px 28px; border-radius: 50px;
        font-weight: 500; font-size: 0.95rem; box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        transition: bottom 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); z-index: 9999; display: flex; align-items: center; gap: 12px;
        border: 1px solid rgba(16, 185, 129, 0.3); white-space: nowrap;
    }
    .sz-toast i { color: #10B981; font-size: 1.2rem; }
    .sz-toast.show { bottom: 40px; }

    @media (max-width: 991px) {
        .hiq-pro-footer-scope .newsletter-box { flex-direction: column; align-items: flex-start; text-align: left; padding: 30px; }
        .hiq-pro-footer-scope .newsletter-form { max-width: 100%; width: 100%; min-width: unset; }
        .hiq-pro-footer-scope .smart-install-box { max-width: 100%; margin-bottom: 30px; }
    }
    @media (max-width: 768px) {
        .hiq-pro-footer-scope .footer-bottom-pro { flex-direction: column; align-items: flex-start; text-align: left;}
        .hiq-pro-footer-scope .ticker-wrap-pro { max-width: 100%; }
    }
</style>

<div class="hiq-pro-footer-scope">
    <footer class="pro-footer">
        <div class="container">
            
            <!-- Floating Newsletter -->
            <div class="newsletter-box">
                <div class="newsletter-text">
                    <h4>HarvestIQ Agri-Alerts</h4>
                    <p>Get daily mandi price updates and weather warnings delivered to your inbox.</p>
                </div>
                <form id="szNewsletterForm" class="newsletter-form">
                    <input type="email" id="subscriberEmail" placeholder="Enter your email address..." required>
                    <button type="submit" class="btn-custom" id="newsBtn">
                        <span id="newsBtnText">Subscribe</span>
                        <i class="fa-solid fa-arrow-right" id="newsBtnIcon"></i>
                    </button>
                </form>
            </div>

            <!-- 5-Column Enterprise Layout -->
            <div class="row g-4">
                <!-- Column 1: Brand & App -->
                <div class="col-lg-3 col-md-12">
                    <a href="index.php" class="sz-brand notranslate" style="display: flex; align-items: center; white-space: nowrap; text-decoration: none;">
    <div class="icon-box" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
        <img src="assets/logo-192.png" alt="HarvestIQ Logo" class="brand-logo" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0px 4px 8px rgba(22, 163, 74, 0.3)); transition: transform 0.3s ease;">
    </div>
    <div class="brand-text notranslate" style="white-space: nowrap;">Harvest<span>IQ</span></div>
</a>
                    <p class="footer-desc">Empowering farmers with real-time market intelligence, weather advisories, and AI-driven crop guidance.</p>
                    
                   <div class="smart-install-box" id="pwaContainer">
    <div class="pro-download-group">
        <button class="pwa-main-btn" id="mainDownloadBtn">
            <i class="fa-brands fa-windows pwa-icon" id="dynamicOsIcon"></i>
            <div class="pwa-text">
                <span class="os-name" id="dynamicOsName">App for Windows</span>
                <span class="arch-name" id="dynamicOsArch">PWA INSTALLER</span>
            </div>
        </button>
    </div>
    <div class="install-help-text" id="installHelpMsg"></div>
</div>
                </div>
                
                <!-- Column 2: Platform -->
                <div class="col-6 col-md-3 col-lg-2 footer-links offset-lg-1">
                    <h4>Platform</h4>
                    <ul>
                        <li><a href="dashboard.php">Farmer Dashboard</a></li>
                        <li><a href="market_prices.php">Live Mandi Prices</a></li>
                        <li><a href="weather.php">Weather Advisory</a></li>
                        <li><a href="crop_recommendation.php">AI Crop Engine</a></li>
                        <li><a href="profile.php">My Profile</a></li>
                    </ul>
                </div>

                <!-- Column 3: Advisory -->
                <div class="col-6 col-md-3 col-lg-2 footer-links">
                    <h4>Advisory</h4>
                    <ul>
                        <li><a href="#">Soil Health Guide</a></li>
                        <li><a href="#">Pesticide Safety</a></li>
                        <li><a href="#">Govt. Schemes</a></li>
                        <li><a href="#">Organic Farming</a></li>
                        <li><a href="#">Irrigation Tips</a></li>
                    </ul>
                </div>

                <!-- Column 4: Company -->
                <div class="col-6 col-md-3 col-lg-2 footer-links">
                    <h4>HarvestIQ</h4>
                    <ul>
                        <li><a href="index.php#mission">Our Mission</a></li>
                        <li><a href="index.php#features">Core Features</a></li>
                        <li><a href="#">Partner NGOs</a></li>
                        <li><a href="#">Agri-Tech Blog</a></li>
                        <li><a href="login_signup.php">Join Network</a></li>
                    </ul>
                </div>

                <!-- Column 5: Contact & Social -->
                <div class="col-6 col-md-3 col-lg-2 footer-links">
                    <h4>Contact Us</h4>
                    <ul class="contact-info">
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <div>
                                <strong>AgriTech Hub</strong>
                                Green Valley, India
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <div>
                                <strong>Email Support</strong>
                                support@harvestiq.ai
                            </div>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <div>
                                <strong>Toll-Free</strong>
                                1800-HARVEST
                            </div>
                        </li>
                    </ul>
                    
                    <div class="social-icons-pro">
                        <a href="#" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="#" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom Area -->
            <div class="footer-bottom-pro">
                <div class="d-flex flex-column gap-1">
                    <p class="mb-0 fw-medium" style="color: #cbd5e1;">&copy; <?php echo date("Y"); ?> HarvestIQ. Designed & Developed by <strong style="color: var(--sz-ft-accent);">Spark Devs</strong>.</p>
                    <div class="footer-bottom-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Settings</a>
                    </div>
                </div>
                
                <div class="ticker-wrap-pro">
                    <span class="ticker-label-pro">ALERT</span>
                    <div class="ticker-content-box">
                        <div class="ticker-text" id="liveTickerText" style="top: 0; opacity: 1;">Loading live agricultural updates...</div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Toast Component -->
<div class="sz-toast" id="szToast"><i class="fa-solid fa-circle-check"></i> <span id="toastMsg">Action completed successfully!</span></div>

<!-- PWA & UI Scripts -->
<script>
    // 🌟 GLOBAL PWA VARIABLE
    let deferredPrompt;
    
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('./sw.js').catch(err => console.log('SW Registration failed: ', err));
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
    });

    // 🌟 INSTALL FUNCTION
    function triggerPWAInstall(systemName) {
        const btnText = document.querySelector('#mainDownloadBtn .os-name');
        const originalText = btnText.innerText;
        
        btnText.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Processing...`;
        
        setTimeout(async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    showToast(`Installing HarvestIQ for ${systemName}...`);
                }
                deferredPrompt = null;
            } else {
                const helpMsg = document.getElementById('installHelpMsg');
                if(/iphone|ipad|ipod/.test(navigator.userAgent.toLowerCase())) {
                    helpMsg.innerHTML = '<i class="fa-solid fa-share-from-square"></i> Tap Share in Safari & select "Add to Home Screen"';
                } else {
                    helpMsg.innerHTML = `<i class="fa-solid fa-circle-info"></i> Direct install unavailable for ${systemName}. Install via browser menu.`;
                }
                helpMsg.style.display = 'block';
            }
            btnText.innerText = originalText;
        }, 800);
    }

    // 🌟 PREMIUM TOAST FUNCTION
    function showToast(message) {
        const toast = document.getElementById('szToast');
        document.getElementById('toastMsg').innerText = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        
        // 🌟 1. OS DETECTION ENGINE
        const userAgent = navigator.userAgent.toLowerCase();
        const osIcon = document.getElementById('dynamicOsIcon');
        const osName = document.getElementById('dynamicOsName');
        
        let isIOS = /ipad|iphone|ipod/.test(userAgent);
        
        if (userAgent.includes('windows')) {
            osIcon.className = 'fa-brands fa-windows pwa-icon';
            osName.innerText = 'App for Windows';
        } else if (userAgent.includes('macintosh') || userAgent.includes('mac os')) {
            osIcon.className = 'fa-brands fa-apple pwa-icon';
            osName.innerText = 'App for macOS';
        } else if (userAgent.includes('android')) {
            osIcon.className = 'fa-brands fa-android pwa-icon';
            osName.innerText = 'App for Android';
        } else if (isIOS) {
            osIcon.className = 'fa-brands fa-apple pwa-icon';
            osName.innerText = 'iOS Web App';
        } else if (userAgent.includes('linux')) {
            osIcon.className = 'fa-brands fa-linux pwa-icon';
            osName.innerText = 'App for Linux';
        }

        // Main Download Button Logic
        document.getElementById('mainDownloadBtn').addEventListener('click', (e) => {
            e.preventDefault();
            triggerPWAInstall('Device');
        });

        // 🌟 2. DROPDOWN TOGGLE LOGIC
        const toggleBtn = document.getElementById('downloadDropdownToggle');
        const dropdownMenu = document.getElementById('downloadMenu');

        toggleBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.pro-download-group')) {
                dropdownMenu.classList.remove('active');
            }
        });

        // 🌟 3. NEWSLETTER LOGIC
        const newsForm = document.getElementById('szNewsletterForm');
        const newsBtn = document.getElementById('newsBtn');
        const newsBtnText = document.getElementById('newsBtnText');
        const newsBtnIcon = document.getElementById('newsBtnIcon');

        newsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            newsBtn.style.pointerEvents = 'none';
            newsBtnText.innerText = 'Verifying...';
            newsBtnIcon.className = 'fa-solid fa-circle-notch fa-spin';

            setTimeout(() => {
                newsForm.reset();
                newsBtnText.innerText = 'Subscribed';
                newsBtnIcon.className = 'fa-solid fa-check';
                newsBtn.style.background = '#10B981'; 
                
                showToast("Welcome! You're now subscribed to HarvestIQ alerts.");

                setTimeout(() => {
                    newsBtn.style.pointerEvents = 'auto';
                    newsBtnText.innerText = 'Subscribe';
                    newsBtnIcon.className = 'fa-solid fa-arrow-right';
                    newsBtn.style.background = ''; 
                }, 4000);
            }, 1500);
        });

        // 🌟 4. DYNAMIC LIVE TICKER (Agri Updates)
        const tickerTextElement = document.getElementById('liveTickerText');
        const tickerMessages = [
            "🌦️ Heavy rainfall expected in northern districts tomorrow.",
            "📈 Wheat prices surged by ₹200/quintal in local mandis.",
            "🌱 New AI feature: Soil health prediction is now live!",
            "🔔 Govt announced new subsidies for organic farming.",
            "💧 Irrigation alert: Maintain soil moisture due to heatwave."
        ];
        
        let currentTickerIndex = 0;

        function updateTicker() {
            tickerTextElement.style.top = '-30px';
            tickerTextElement.style.opacity = '0';
            
            setTimeout(() => {
                currentTickerIndex = (currentTickerIndex + 1) % tickerMessages.length;
                tickerTextElement.innerText = tickerMessages[currentTickerIndex];
                
                tickerTextElement.style.transition = 'none';
                tickerTextElement.style.top = '30px';
                
                setTimeout(() => {
                    tickerTextElement.style.transition = 'top 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55), opacity 0.6s ease';
                    tickerTextElement.style.top = '0';
                    tickerTextElement.style.opacity = '1';
                }, 50);
            }, 600); 
        }

        tickerTextElement.innerText = tickerMessages[0];
        setInterval(updateTicker, 5000);
    });
</script>