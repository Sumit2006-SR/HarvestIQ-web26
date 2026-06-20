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

















<style>
    .hiq-floating-dock {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 12px 10px;
        border-radius: 50px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 9999;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        animation: slideInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    [data-theme="light"] .hiq-floating-dock {
        background: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .dock-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.05);
        color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        position: relative;
    }

    [data-theme="light"] .dock-btn {
        background: #f1f5f9;
        color: #0f172a;
    }

    .dock-btn:hover {
        background: #10b981;
        color: #fff;
        transform: scale(1.1) translateX(-5px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    /* SOS Emergency Button Special Styling */
    .dock-btn.sos-btn {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        animation: sosPulse 2s infinite;
    }
    
    .dock-btn.sos-btn:hover {
        background: #ef4444;
        color: #fff;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        animation: none;
    }

    @keyframes sosPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
    }

    /* Scroll to Top Hidden by Default */
    #scrollTopBtn {
        opacity: 0;
        visibility: hidden;
        transform: scale(0.5);
    }
    #scrollTopBtn.show {
        opacity: 1;
        visibility: visible;
        transform: scale(1);
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translate(50px, -50%); }
        to { opacity: 1; transform: translate(0, -50%); }
    }

    /* Tooltip / Label on Hover */
    .dock-btn::before {
        content: attr(data-tooltip);
        position: absolute;
        right: 55px;
        background: var(--bg-card, #0f172a);
        color: #fff;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: 0.3s;
        pointer-events: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .dock-btn:hover::before {
        opacity: 1;
        visibility: visible;
        right: 60px;
    }

    @media (max-width: 768px) {
        .hiq-floating-dock { right: 10px; padding: 10px 8px; }
        .dock-btn { width: 38px; height: 38px; font-size: 1rem; }
        .dock-btn::before { display: none; /* Hide tooltips on mobile */ }
    }
</style>

<div class="hiq-floating-dock">

<button class="dock-btn" onclick="openZyneAssistant()" data-tooltip="Ask Zyne AI">
        <i class="fa-solid fa-robot"></i>
    </button>

    <button class="dock-btn" onclick="shareHarvestIQ()" data-tooltip="Share Platform">
        <i class="fa-solid fa-share-nodes"></i>
    </button>
    
     

    <a href="tel:18001801551" class="dock-btn sos-btn" data-tooltip="Kisan SOS (1800-180-1551)">
        <i class="fa-solid fa-phone-volume"></i>
    </a>

    <button class="dock-btn" id="scrollTopBtn" onclick="scrollToTop()" data-tooltip="Back to Top">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
</div>

<script>
    // 1. Native Web Share API Logic (Looks incredibly professional on presentation)
    async function shareHarvestIQ() {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: 'HarvestIQ - Smart Agriculture',
                    text: 'Join HarvestIQ to get real-time weather alerts and AI crop advisories!',
                    url: window.location.href
                });
            } catch (err) {
                console.log('Share canceled or failed.', err);
            }
        } else {
            // Fallback for browsers that don't support native share
            navigator.clipboard.writeText(window.location.href);
            alert("Platform link copied to clipboard!");
        }
    }

    // 2. Scroll to Top Logic
    const scrollTopBtn = document.getElementById("scrollTopBtn");
    
    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            scrollTopBtn.classList.add("show");
        } else {
            scrollTopBtn.classList.remove("show");
        }
    });

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
</script>



<!-- ==========================================================================
     ZYNE AI CHAT WIDGET - PREMIUM GLASSMORPHISM
     ========================================================================== -->
<style>
    .zyne-chat-widget {
        position: fixed;
        bottom: 80px; /* Sits just above the bottom */
        right: 80px;  /* Offset to avoid overlapping the floating dock */
        width: 350px;
        height: 500px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), inset 0 0 0 1px rgba(16, 185, 129, 0.15);
        z-index: 10000;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        
        /* Hidden state by default */
        transform: translateY(30px) scale(0.95);
        opacity: 0;
        visibility: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Active State Triggered by openZyneAssistant() */
    .zyne-chat-widget.active {
        transform: translateY(0) scale(1);
        opacity: 1;
        visibility: visible;
    }

    .zyne-header {
        background: rgba(255, 255, 255, 0.03);
        padding: 18px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .zyne-header h5 {
        margin: 0;
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .zyne-header h5 i {
        color: #10b981;
    }

    .zyne-close-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 1.2rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    .zyne-close-btn:hover {
        color: #ef4444;
    }

    .zyne-body {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .zyne-message {
        background: rgba(255, 255, 255, 0.05);
        padding: 12px 16px;
        border-radius: 14px;
        border-bottom-left-radius: 4px;
        color: #e2e8f0;
        font-size: 0.9rem;
        line-height: 1.5;
        max-width: 85%;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .zyne-footer {
        padding: 15px;
        background: rgba(2, 6, 23, 0.5);
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        gap: 10px;
    }

    .zyne-footer input {
        flex: 1;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 10px 20px;
        color: #fff;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.3s;
    }

    .zyne-footer input:focus {
        border-color: #10b981;
        background: rgba(255, 255, 255, 0.1);
    }

    .zyne-send-btn {
        background: #10b981;
        color: #fff;
        border: none;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .zyne-send-btn:hover {
        background: #059669;
        transform: scale(1.05);
    }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .zyne-chat-widget {
            right: 15px;
            bottom: 70px;
            width: calc(100% - 30px);
            height: 400px;
        }
    }



    /* Align user messages to the right and style them */
.zyne-message.user {
    align-self: flex-end;
    background: rgba(16, 185, 129, 0.2); /* Soft Emerald */
    border: 1px solid rgba(16, 185, 129, 0.4);
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 14px;
}

/* Typing Dots Animation */
.typing-dots span {
    display: inline-block; width: 6px; height: 6px; background: #94A3B8;
    border-radius: 50%; margin: 0 2px; animation: typing 1.4s infinite ease-in-out both;
}
.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }




/* Dynamic Message Styling for Dark Theme */
.zyne-message {
    display: flex;
    max-width: 88%;
    animation: fadeIn 0.3s ease;
    background: transparent !important; /* Overriding old background */
    border: none !important;
    padding: 0 !important;
}

.zyne-message.bot { align-self: flex-start; }
.zyne-message.user { align-self: flex-end; }

.msg-bubble {
    padding: 12px 16px;
    border-radius: 14px;
    font-size: 0.9rem;
    line-height: 1.5;
    word-wrap: break-word;
}

.zyne-message.bot .msg-bubble {
    background: rgba(255, 255, 255, 0.05);
    color: #e2e8f0;
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-bottom-left-radius: 4px;
}

.zyne-message.user .msg-bubble {
    background: rgba(16, 185, 129, 0.2); /* Emerald Tint */
    color: #ffffff;
    border: 1px solid rgba(16, 185, 129, 0.3);
    border-bottom-right-radius: 4px;
}

/* Typing Dots */
.typing-dots span {
    display: inline-block; width: 6px; height: 6px; background: #94A3B8;
    border-radius: 50%; margin: 0 2px; animation: typing 1.4s infinite ease-in-out both;
}
.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typing { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }
</style>

<div class="zyne-chat-widget" id="zyneChatWidget">
    <div class="zyne-header">
        <h5><i class="fa-solid fa-robot"></i> Zyne AI Assistant</h5>
        <button class="zyne-close-btn" onclick="closeZyneAssistant()">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
    
    <div class="zyne-body" id="zyneChatBody">
    <div class="zyne-message bot">
        <div class="msg-bubble">
            👋 Hello! I am Zyne, your HarvestIQ AI Assistant. Ask me about weather forecasts, live mandi prices, or crop disease management!
        </div>
    </div>
</div>
    
<div class="zyne-footer">
    <input type="text" id="zyneInput" placeholder="Type your question..." autocomplete="off" onkeypress="handleZyneEnter(event)">
    
    <button class="zyne-send-btn" id="zyneSendBtn" onclick="sendZyneMessage()">
        <i class="fa-solid fa-paper-plane"></i>
    </button>
</div>
</div>
<script>
// ==========================================
// 🤖 DYNAMIC ZYNE AI CHAT LOGIC (Dark Theme)
// ==========================================

// Chat History Array to remember context
let zyneChatHistory = []; 

function openZyneAssistant() {
    const widget = document.getElementById('zyneChatWidget');
    if(widget) {
        widget.classList.add('active');
        setTimeout(() => { document.getElementById('zyneInput').focus(); }, 300);
    }
}

function closeZyneAssistant() {
    const widget = document.getElementById('zyneChatWidget');
    if(widget) { widget.classList.remove('active'); }
}

// Close chat when clicking outside
document.addEventListener('click', function(event) {
    const widget = document.getElementById('zyneChatWidget');
    const aiDockBtn = document.querySelector('[data-tooltip="Ask Zyne AI"]');
    
    if (widget && widget.classList.contains('active') && 
        !widget.contains(event.target) && 
        !aiDockBtn.contains(event.target)) {
        closeZyneAssistant();
    }
});

function handleZyneEnter(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        sendZyneMessage();
    }
}

async function sendZyneMessage() {
    const inputField = document.getElementById('zyneInput');
    const sendBtn = document.getElementById('zyneSendBtn');
    const message = inputField.value.trim();
    const chatBody = document.getElementById('zyneChatBody');

    if (message === "") return;

    // Disable input while processing
    inputField.disabled = true;
    sendBtn.disabled = true;

    // Show User Message
    chatBody.innerHTML += `<div class="zyne-message user"><div class="msg-bubble">${message}</div></div>`;
    inputField.value = ""; 
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' });

    // Show AI Typing
    const typingId = "typing-" + Date.now();
    chatBody.innerHTML += `
        <div class="zyne-message bot" id="${typingId}">
            <div class="msg-bubble typing-dots">
                <span></span><span></span><span></span>
            </div>
        </div>
    `;
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' });

    try {
        // NOTE: Ensure 'zyne_chat_api.php' is the correct path to your PHP file
        const response = await fetch('zyne_chat_api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                message: message,
                history: zyneChatHistory 
            })
        });

        const data = await response.json();
        document.getElementById(typingId).remove();

        // Format Bold Text
        let formattedReply = data.reply ? data.reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') : "Error: No reply from server.";
        
        // Show Bot Message
        chatBody.innerHTML += `<div class="zyne-message bot"><div class="msg-bubble">${formattedReply}</div></div>`;
        
        // Save to history
        zyneChatHistory.push({ role: "user", parts: [{ text: message }] });
        zyneChatHistory.push({ role: "model", parts: [{ text: data.reply }] });

    } catch (error) {
        document.getElementById(typingId).remove();
        chatBody.innerHTML += `<div class="zyne-message bot"><div class="msg-bubble" style="color: #EF4444;">Connection error. Try again!</div></div>`;
    }

    // Re-enable input
    inputField.disabled = false;
    sendBtn.disabled = false;
    inputField.focus();
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: 'smooth' });
}
</script>