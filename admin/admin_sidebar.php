<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="admin.css?v=1.0">
<script src="../assets/js/theme.js" defer></script>

<style>
/* ==========================================
   PREMIUM ADMIN UI/UX & LOADER STYLES
========================================== */
:root {
    --sidebar-bg: #ffffff;
    --sidebar-border: #e2e8f0;
    --menu-text: #475569;
    --menu-hover-bg: #f1f5f9;
    --menu-active-bg: rgba(16, 185, 129, 0.1);
    --menu-active-text: #10b981;
    --modal-bg: rgba(255, 255, 255, 0.95);
    --modal-text: #0f172a;
    --overlay-bg: rgba(15, 23, 42, 0.4);
}

[data-theme="dark"] {
    --sidebar-bg: #0f172a;
    --sidebar-border: rgba(255, 255, 255, 0.08);
    --menu-text: #94a3b8;
    --menu-hover-bg: rgba(255, 255, 255, 0.05);
    --menu-active-bg: rgba(16, 185, 129, 0.15);
    --menu-active-text: #34d399;
    --modal-bg: rgba(15, 23, 42, 0.95);
    --modal-text: #f8fafc;
    --overlay-bg: rgba(0, 0, 0, 0.6);
}

/* --- Smooth Page Loader --- */
.hiq-page-loader {
    position: fixed; inset: 0; background: var(--sidebar-bg); z-index: 999999;
    display: flex; align-items: center; justify-content: center;
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s;
}
.hiq-page-loader.hidden { opacity: 0; visibility: hidden; }
.loader-content { text-align: center; display: flex; flex-direction: column; align-items: center; gap: 15px;}
.loader-content i {
    font-size: 3rem; color: #10b981;
    animation: pulseIcon 1.5s ease-in-out infinite;
    filter: drop-shadow(0 0 15px rgba(16, 185, 129, 0.4));
}
.loader-content p {
    font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--menu-text); font-size: 0.95rem; letter-spacing: 1px; text-transform: uppercase;
}
@keyframes pulseIcon {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.7; }
}

/* --- Sidebar Refinements --- */
.admin-sidebar {
    background: var(--sidebar-bg);
    border-right: 1px solid var(--sidebar-border);
    transition: background 0.3s ease, border-color 0.3s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.sidebar-menu a {
    color: var(--menu-text);
    border-radius: 12px;
    margin: 4px 15px;
    padding: 12px 18px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: flex; align-items: center; gap: 12px;
    position: relative; overflow: hidden;
}

.sidebar-menu a i { font-size: 1.1rem; transition: 0.3s; width: 20px; text-align: center;}

.sidebar-menu a:hover {
    background: var(--menu-hover-bg);
    transform: translateX(4px);
    color: var(--menu-active-text);
}

.sidebar-menu a.active {
    background: var(--menu-active-bg);
    color: var(--menu-active-text);
    font-weight: 700;
}
.sidebar-menu a.active::before {
    content: ''; position: absolute; left: 0; top: 15%; height: 70%; width: 4px;
    background: var(--menu-active-text); border-radius: 0 4px 4px 0;
}

.sidebar-section-label {
    margin: 20px 20px 8px; font-size: 0.75rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: 1.2px; color: #64748b; opacity: 0.8;
}

/* --- Premium Logout Modal --- */
.logout-modal-overlay {
    position: fixed; inset: 0; background: var(--overlay-bg); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    z-index: 100000; display: flex; align-items: center; justify-content: center;
    opacity: 0; visibility: hidden; transition: all 0.4s ease;
}
.logout-modal-overlay.show { opacity: 1; visibility: visible; }

.logout-modal-box {
    background: var(--modal-bg); border: 1px solid var(--sidebar-border);
    border-radius: 24px; padding: 40px 30px; max-width: 400px; width: 90%;
    text-align: center; box-shadow: 0 25px 50px rgba(0,0,0,0.2);
    transform: scale(0.9) translateY(20px); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.logout-modal-overlay.show .logout-modal-box { transform: scale(1) translateY(0); }

.logout-icon {
    width: 60px; height: 60px; background: rgba(239, 68, 68, 0.1); border-radius: 50%;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
    color: #ef4444; font-size: 1.5rem; border: 1px solid rgba(239, 68, 68, 0.2);
}

.logout-modal-box h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; color: var(--modal-text); margin-bottom: 10px; }
.logout-modal-box p { color: var(--menu-text); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.5; }

.logout-actions { display: flex; gap: 15px; }
.logout-actions button, .logout-actions a {
    flex: 1; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 0.95rem;
    cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-block;
}
.btn-cancel { background: var(--menu-hover-bg); color: var(--menu-text); border: none; }
.btn-cancel:hover { background: #e2e8f0; color: #0f172a; }
[data-theme="dark"] .btn-cancel:hover { background: rgba(255,255,255,0.1); color: #fff; }

.btn-confirm { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border: none; box-shadow: 0 10px 20px rgba(239,68,68,0.3); }
.btn-confirm:hover { transform: translateY(-2px); box-shadow: 0 15px 25px rgba(239,68,68,0.4); color: #fff;}
</style>

<!-- 1. Smooth Page Loader -->
<div id="hiq-page-loader" class="hiq-page-loader">
    <div class="loader-content">
        <i class="fa-solid fa-leaf"></i>
        <p>Initializing Workspace</p>
    </div>
</div>

<div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

<aside class="admin-sidebar" id="adminSidebar">
    <a href="admin_dashboard.php" class="sz-admin-brand" style="text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 30px 20px 15px;">
    <div class="admin-brand-main" style="display: flex; align-items: center; justify-content: center; gap: 10px;">
        
        <!-- আপনার লোগো -->
        <div class="admin-icon-box" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
            <img src="../assets/logo-192.png" alt="HarvestIQ Logo" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        
        <div class="admin-brand-text" style="white-space: nowrap; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.4rem; color: var(--modal-text);">Harvest<span style="color:#10b981;">IQ</span></div>
    </div>
    
    <!-- সেন্টারে থাকা অ্যাডমিন ব্যাজ -->
    <span class="admin-badge" style="background: rgba(16,185,129,0.1); color: #10b981; padding: 5px 14px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">Admin Workspace</span>
</a>

    <div class="admin-sidebar-tools" style="padding: 15px 20px; border-bottom: 1px solid var(--sidebar-border); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
        <small style="font-weight: 700; color: var(--menu-text); font-size: 0.8rem;">THEME SETTINGS</small>
        <button type="button" class="hiq-theme-toggle" data-hiq-theme-toggle aria-label="Switch theme">
            <span class="hiq-theme-toggle-track"></span>
            <span class="hiq-theme-toggle-thumb">
                <i class="fa-solid fa-sun icon-sun"></i>
                <i class="fa-solid fa-moon icon-moon"></i>
            </span>
        </button>
    </div>

    <nav class="sidebar-menu">
        <a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-pie"></i> Dashboard Overview
        </a>

        <span class="sidebar-section-label">User Management</span>
        <a href="manage_users.php" class="<?php echo ($current_page == 'manage_users.php' || $current_page == 'add_user.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-users"></i> Farmer Profiles
        </a>

        <span class="sidebar-section-label">Data & Intelligence</span>
        <a href="manage_prices.php" class="<?php echo ($current_page == 'manage_prices.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-chart-line"></i> Live Market Prices
        </a>
        
        <a href="admin_weather.php" class="<?php echo ($current_page == 'admin_weather.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-tower-broadcast"></i> Weather Intelligence
        </a>
        
        <a href="manage_crops.php" class="<?php echo ($current_page == 'manage_crops.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-leaf"></i> Crop Knowledge Base
        </a>
    </nav>

    <div class="sidebar-footer" style="padding: 20px; border-top: 1px solid var(--sidebar-border); margin-top: auto;">
        <a href="../index.php" target="_blank" style="display:flex; align-items:center; gap:10px; color: var(--menu-text); font-weight: 600; text-decoration:none; margin-bottom:15px; transition:0.3s;"><i class="fa-solid fa-globe"></i> Visit Live Portal</a>
        
        <!-- Updated Logout Link to trigger Modal -->
        <a href="javascript:void(0)" onclick="openLogoutModal()" style="display:flex; align-items:center; gap:10px; color: #ef4444; font-weight: 700; text-decoration:none; transition:0.3s;"><i class="fa-solid fa-right-from-bracket"></i> Secure Logout</a>
    </div>
</aside>

<button type="button" class="admin-hamburger" id="adminHamburger" aria-label="Toggle admin menu" style="position:fixed; top:20px; left:20px; z-index:999; background: var(--sidebar-bg); border: 1px solid var(--sidebar-border); color: var(--modal-text); padding: 10px 14px; border-radius: 12px; cursor:pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <i class="fa-solid fa-bars-staggered"></i>
</button>

<!-- 2. Premium Logout Modal -->
<div id="logoutModal" class="logout-modal-overlay">
    <div class="logout-modal-box">
        <div class="logout-icon"><i class="fa-solid fa-lock"></i></div>
        <h3>Leaving Workspace?</h3>
        <p>Are you sure you want to securely log out of your session? You will need to re-authenticate to access the dashboard.</p>
        <div class="logout-actions">
            <button onclick="closeLogoutModal()" class="btn-cancel">Stay Logged In</button>
            <a href="../logout.php" class="btn-confirm">Log Out Now</a>
        </div>
    </div>
</div>

<script>
// --- Loader Logic ---
window.addEventListener('load', function() {
    const loader = document.getElementById('hiq-page-loader');
    if(loader) {
        setTimeout(() => {
            loader.classList.add('hidden');
            setTimeout(() => loader.style.display = 'none', 600);
        }, 400); // 400ms delay for smooth perception
    }
});

// --- Modal Logic ---
function openLogoutModal() {
    document.getElementById('logoutModal').classList.add('show');
}
function closeLogoutModal() {
    document.getElementById('logoutModal').classList.remove('show');
}

// Close modal if clicked outside the box
document.getElementById('logoutModal').addEventListener('click', function(e) {
    if(e.target === this) closeLogoutModal();
});

// --- Sidebar Logic ---
document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('adminSidebarOverlay');
    var btn = document.getElementById('adminHamburger');

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('open');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    function openSidebar() {
        if (sidebar) sidebar.classList.add('open');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    if (btn) {
        btn.addEventListener('click', function () {
            if (sidebar && sidebar.classList.contains('open')) closeSidebar();
            else openSidebar();
        });
    }
    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.querySelectorAll('.sidebar-menu a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 991) closeSidebar();
        });
    });
});
</script>