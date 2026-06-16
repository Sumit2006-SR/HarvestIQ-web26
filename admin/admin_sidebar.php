<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="admin.css?v=1.0">
<script src="../assets/js/theme.js" defer></script>

<div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>

<aside class="admin-sidebar" id="adminSidebar">
   <a href="admin_dashboard.php" class="sz-admin-brand" style="text-decoration: none; display: flex; flex-direction: column; align-items: flex-start; gap: 4px;">
    <div class="admin-brand-main" style="display: flex; align-items: center; gap: 10px;">
        <div class="admin-icon-box">
            <i class="fa-solid fa-seedling admin-brand-icon"></i>
        </div>
        <div class="admin-brand-text" style="white-space: nowrap;">Harvest<span>IQ</span></div>
    </div>
    <span class="admin-badge" style="margin-left: 55px;">Admin Workspace</span>
</a>

    <div class="admin-sidebar-tools">
        <small>Appearance</small>
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
            <i class="fa-solid fa-users"></i> Manage Farmers
        </a>

        <span class="sidebar-section-label">Data & Content</span>
        <a href="manage_prices.php" class="<?php echo ($current_page == 'manage_prices.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-indian-rupee-sign"></i> Update Mandi Prices
        </a>
        
        <a href="admin_weather.php" class="<?php echo ($current_page == 'admin_weather.php') ? 'active' : ''; ?>">
      <i class="fa-solid fa-cloud-bolt"></i> Weather Radar
    </a>
        <a href="manage_crops.php" class="<?php echo ($current_page == 'manage_crops.php') ? 'active' : ''; ?>">
            <i class="fa-solid fa-seedling"></i> Manage Crops
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="../index.php" target="_blank"><i class="fa-solid fa-globe"></i> Visit Live Portal</a>
        <a href="../logout.php" class="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Secure Logout</a>
    </div>
</aside>

<button type="button" class="admin-hamburger" id="adminHamburger" aria-label="Toggle admin menu">
    <i class="fa-solid fa-bars"></i>
</button>

<script>
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

    document.querySelectorAll('.sidebar-menu a, .sidebar-footer a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 991) closeSidebar();
        });
    });
});
</script>
