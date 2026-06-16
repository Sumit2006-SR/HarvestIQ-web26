<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/all.min.css">
<link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#0F172A">
<link rel="apple-touch-icon" href="assets/logo-192.png">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">
<script>(function(){try{document.documentElement.setAttribute('data-theme',localStorage.getItem('harvestiq-theme')||'light');}catch(e){}})();</script>

<?php
require 'db.php';
$is_logged_in = isset($_SESSION['user_id']);
$display_name = "Guest";
$user_initial = "G"; 
$is_verified = 0;
$total_unread = 0;
$badge_text = '';
$badge_display = 'none';

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT full_name, email, is_verified FROM users WHERE id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_data = $stmt->get_result()->fetch_assoc();

        if ($user_data) {
            $full_name = !empty($user_data['full_name']) ? trim($user_data['full_name']) : explode('@', $user_data['email'])[0];
            $name_parts = explode(' ', $full_name);  
            $display_name = $full_name;            
            if (count($name_parts) >= 2) {
                 $user_initial = strtoupper(substr($name_parts[0], 0, 1) . substr($name_parts[1], 0, 1));
            } else {
                 $user_initial = strtoupper(substr($full_name, 0, 1));
            }
            $is_verified = $user_data['is_verified'] ?? 0;
        }
        $stmt->close();
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="style.css?v=3.0" data-sz-nav-styles="1">
<script src="assets/js/theme.js" defer></script>

<style>

    .sz-navbar-scope {
        --sz-nav-glass: var(--nav-glass, rgba(255, 255, 255, 0.85));
        --sz-primary: #16a34a;
        --sz-primary-hover: #15803d;
        --sz-text-dark: var(--nav-text, #0f172a);
        --sz-text-muted: var(--nav-muted, #64748b);
        --sz-border-soft: var(--nav-border, #e2e8f0);
        --sz-bg-light: var(--surface-2, #f8fafc);
    }

    [data-theme="dark"] .sz-navbar-scope {
        --sz-nav-glass: rgba(15, 23, 42, 0.92);
        --sz-text-dark: #f8fafc;
        --sz-text-muted: #94a3b8;
        --sz-border-soft: rgba(255,255,255,0.08);
        --sz-bg-light: rgba(30, 41, 59, 0.6);
    }

    [data-theme="dark"] .sz-nav-wrapper.scrolled .sz-nav-inner {
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4) !important;
    }

    [data-theme="dark"] .sz-mobile-menu-btn { color: #f8fafc !important; }
    [data-theme="dark"] .btn-sz-widget { background: rgba(30,41,59,0.8); color: #f8fafc; border-color: rgba(255,255,255,0.1); }
    [data-theme="dark"] .sz-dropdown-menu { background: rgba(15,23,42,0.98); border-color: rgba(255,255,255,0.1); }
    [data-theme="dark"] .sz-action-text strong, [data-theme="dark"] .sz-action-item span { color: #f8fafc !important; }
    [data-theme="dark"] #szMobileNavLinks.sz-nav-links-container { background: #0f172a !important; border-color: rgba(255,255,255,0.08) !important; }
    [data-theme="dark"] .sz-mobile-nav-header { background: #0f172a; border-color: rgba(255,255,255,0.08); }
    [data-theme="dark"] .sz-mobile-nav-title { color: #f8fafc; }
    [data-theme="dark"] .sz-mobile-nav-list .nav-link { color: #cbd5e1; }
    [data-theme="dark"] .sz-top-nav .nav-link { color: #94a3b8; }
    [data-theme="dark"] .sz-top-nav .nav-link:hover,
    [data-theme="dark"] .sz-top-nav .nav-link.active { color: #f8fafc !important; }
    [data-theme="dark"] .sz-brand .brand-text { color: #f8fafc; }

    .sz-nav-wrapper {
        position: sticky !important; 
        top: 0 !important; 
        left: 0 !important; 
        width: 100% !important; 
        z-index: 1050 !important; 
        padding: 20px 4% !important; 
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        font-family: 'Poppins', sans-serif !important;
    }

    .sz-nav-wrapper.scrolled { 
        padding: 10px 4% !important; 
    }
    
    .sz-nav-inner {
        max-width: 1320px !important; 
        margin: 0 auto !important; 
        display: flex !important; 
        justify-content: space-between !important; 
        align-items: center !important; 
        padding: 10px 5px !important; 
        background: transparent !important; 
        border: 1px solid transparent !important;
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) !important; 
        position: relative !important;
    }
    
    .sz-nav-wrapper.scrolled .sz-nav-inner {
        padding: 10px 25px !important; 
        background: var(--sz-nav-glass) !important; 
        backdrop-filter: blur(20px) !important; 
        -webkit-backdrop-filter: blur(20px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important; 
        border-radius: 50px !important;
    }

    /* ডেস্কটপে মোবাইল আইকনটি ফোর্সফুলি হাইড করার কোড */
    .sz-mobile-menu-btn {
        display: none !important; 
        background: none !important; 
        border: none !important; 
        font-size: 1.5rem !important; 
        color: var(--sz-text-dark) !important; 
        cursor: pointer !important;
    }
    
    @media (max-width: 991.98px) {
        .sz-mobile-menu-btn { display: inline-flex !important; }
    }

    .sz-navbar-scope {
        --sz-nav-glass: rgba(255, 255, 255, 0.85); 
        --sz-primary: #16a34a; 
        --sz-primary-hover: #15803d;
        --sz-text-dark: #0f172a; 
        --sz-text-muted: #64748b; 
        --sz-border-soft: #e2e8f0;
        --sz-bg-light: #f8fafc;
    }

    .sz-top-nav .nav-link:hover,
    .sz-top-nav .nav-link.active {
        color: #0F172A !important;  
        background-color: transparent !important;
        transform: translateY(-2px);  
    }

    .sz-top-nav .nav-link:hover::before,
    .sz-top-nav .nav-link.active::before {
        transform: scale(1);
        opacity: 1;
    }

    .sz-top-nav .nav-link:hover::after,
    .sz-top-nav .nav-link.active::after {
        transform: translateX(-50%) translateY(0) scale(1);
        width: 20px;  
        opacity: 1;
    }

    .sz-nav-wrapper {
        position: fixed; top: 0; left: 0; width: 100%; z-index: 1050; padding: 20px 4%; 
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
        font-family: 'Poppins', sans-serif;
    }
    .sz-nav-wrapper.scrolled { padding: 10px 4%; }
    
    .sz-nav-inner {
        max-width: 1320px; margin: 0 auto; display: flex; justify-content: space-between; 
        align-items: center; padding: 10px 5px; background: transparent; border: 1px solid transparent;
        transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1); position: relative;
    }
    
    .sz-nav-wrapper.scrolled .sz-nav-inner {
        padding: 10px 25px; 
        background: var(--sz-nav-glass);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06); 
        border-radius: 50px;
    }

.sz-brand {
    display: flex; 
    align-items: center; 
    gap: 8px; 
    text-decoration: none; 
    font-family: 'Outfit', 'Clash Display', sans-serif; 
    font-size: 2rem; 
    font-weight: 900; 
    letter-spacing: -0.8px; 
    color: #06205b; 
    z-index: 5;
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    margin-right:10px;
}

.sz-brand span { 
    background: linear-gradient(135deg, #16a34a 0%, #047857 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
}

.sz-brand .icon-box {
    width: 50px; 
    height: 50px;
    display: flex; 
    align-items: center; 
    justify-content: center; 
    background: transparent; 
    position: relative; 
}

.sz-brand .icon-box::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(22, 163, 74, 0.15) 0%, transparent 70%);
    transform: scale(1.5);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.5s ease;
}

.sz-brand .brand-logo {
    width: 100%;
    height: 100%;
    object-fit: contain; 
    filter: drop-shadow(0px 8px 16px rgba(22, 163, 74, 0.25));
    transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.5s ease;
}

.sz-brand:hover {
    transform: translateY(-2px);
}

.sz-brand:hover .icon-box::before {
    opacity: 1;
}

.sz-brand:hover .brand-logo {
    transform: scale(1.12) rotate(6deg); 
    filter: drop-shadow(0px 12px 24px rgba(22, 163, 74, 0.45));
}
    .sz-center-container { flex: 1; display: flex; justify-content: center; align-items: center; position: relative; }
    .sz-menu { display: flex; align-items: center; gap: 5px; transition: all 0.4s ease; }
    .sz-divider { width: 1px; height: 20px; background-color: var(--sz-border-soft); margin: 0 12px; }
    
    .sz-scope-badge {
        display: flex; align-items: center; gap: 6px; font-size: 0.85rem; font-weight: 600; color: var(--sz-text-dark); 
        background: var(--sz-bg-light); padding: 8px 16px; border-radius: 50px; border: 1px solid var(--sz-border-soft); 
        transition: all 0.3s ease; cursor: pointer;
    }
    .sz-scope-badge:hover { background: #fff; border-color: var(--sz-primary); box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
    .sz-scope-badge i.fa-earth-americas { color: var(--sz-primary); }

    .sz-action-hub { position: relative; margin-right: 15px; }
    .btn-sz-widget {
        background: #ffffff; color: var(--sz-text-dark); 
        border: 1px solid var(--sz-border-soft); padding: 6px 18px 6px 6px; 
        border-radius: 50px; font-weight: 600; font-size: 0.9rem; cursor: pointer;
        display: flex; align-items: center; gap: 12px; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .btn-sz-widget:hover, .btn-sz-widget.active { border-color: var(--sz-primary); background: rgba(22, 163, 74, 0.05); box-shadow: 0 6px 15px rgba(22, 163, 74, 0.1); }
    
    .widget-icon-circle {
        width: 32px; height: 32px; background: linear-gradient(135deg, var(--sz-primary), #047857); color: #fff;
        border-radius: 50px; display: flex; align-items: center; justify-content: center; font-size: 1rem;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        box-shadow: 0 2px 8px rgba(22, 163, 74, 0.4);
    }
    .btn-sz-widget.active .widget-icon-circle { transform: rotate(135deg); background: linear-gradient(135deg, #15803d, #14532d); }

    .sz-dropdown-menu {
        position: absolute; top: 130%; right: 0; width: 300px;
        background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(20px); 
        border: 1px solid var(--sz-border-soft); border-radius: 16px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);
        opacity: 0; visibility: hidden; transform: translateY(15px) scale(0.95);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); z-index: 1060; padding: 12px;
    }
    .sz-dropdown-menu.active { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

    .sz-action-item {
        display: flex; align-items: center; gap: 15px; padding: 12px; border-radius: 12px; 
        text-decoration: none !important; transition: all 0.3s ease; margin-bottom: 5px;
        background: transparent;
    }
    .sz-action-item:hover { background: var(--sz-bg-light); transform: translateX(5px); }
    .sz-action-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .sz-action-text strong { color: var(--sz-text-dark); font-size: 0.95rem; display: block; margin-bottom: 2px; font-weight: 700;}
    .sz-action-text span { color: var(--sz-text-muted); font-size: 0.8rem; }

    .action-teach .sz-action-icon { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
    .action-learn .sz-action-icon { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }

    .sz-profile-btn {
        width: 42px; height: 42px; border-radius: 50%; border: 2px solid var(--sz-primary);
        background: var(--sz-bg-light); color: var(--sz-text-dark); display: flex; align-items: center; justify-content: center;
        cursor: pointer; padding: 0; overflow: hidden; font-weight: 700; transition: 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .sz-profile-btn img { width: 100%; height: 100%; object-fit: cover; }
    .sz-profile-btn:hover { box-shadow: 0 6px 15px rgba(22, 163, 74, 0.2); transform: translateY(-2px); }

    .btn-login { color: var(--sz-text-dark); font-weight: 600; text-decoration: none; margin-right: 15px; transition: 0.3s; }
    .btn-login:hover { color: var(--sz-primary); }
    .btn-register { 
        background: linear-gradient(135deg, var(--sz-primary), #10b981); color: #fff; padding: 8px 24px; border-radius: 50px; 
        font-weight: 600; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 15px rgba(22, 163, 74, 0.25);
    }
    .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(22, 163, 74, 0.35); color:#fff; }

    .action-icon-link { color: var(--sz-text-muted); font-size: 1.25rem; transition: 0.3s; }
    .action-icon-link:hover { color: var(--sz-primary); transform: translateY(-2px); }

    @media (max-width: 991px) {
        .sz-nav-wrapper { padding: 15px 5%; }
        .sz-center-container { display: none; } 
    }

.sz-scope-badge {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 6px 16px 6px 8px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.sz-scope-badge:hover {
    border-color: #3b82f6;
    background: #f8fafc;
}

.scope-icon-box {
    width: 32px;
    height: 32px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

.scope-content {
    display: flex;
    flex-direction: column;
    text-align: left;
}

.scope-content small {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #64748b;
    line-height: 1;
    margin-bottom: 2px;
}

.scope-content span {
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    line-height: 1;
}

.sz-scope-menu {
    position: absolute;
    top: 120%;
    left: 0;
    width: 260px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    padding: 8px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: 0.3s;
    z-index: 1000;
}

.sz-scope-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.menu-header {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    padding: 10px 12px;
    text-transform: uppercase;
}

.scope-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    text-decoration: none !important;
    transition: 0.2s;
}

.scope-item:hover {
    background: #f1f5f9;
}

.scope-item i {
    font-size: 16px;
    color: #64748b;
    width: 20px;
    text-align: center;
}

.scope-info strong {
    display: block;
    font-size: 13px;
    color: #1e293b;
}

.scope-info span {
    font-size: 11px;
    color: #64748b;
}

.scope-item.active {
    background: #eff6ff;
}
.scope-item.active i, .scope-item.active strong {
    color: #2563eb;
}

        .nav-unread-badge {
            position: absolute;
            top: -4px;
            right: -6px;
            background: linear-gradient(135deg, #16a34a, #047857);  
            color: #FFFFFF;
            font-size: 10px;
            font-weight: 800;
            height: 18px;
            min-width: 18px;
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border: 2px solid #FFFFFF;  
            box-shadow: 0 4px 10px rgba(22, 163, 74, 0.4);  
            animation: badge-pulse 2s infinite cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
        }

         @keyframes badge-pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 4px 10px rgba(22, 163, 74, 0.4); }
            50% { transform: scale(1.15); box-shadow: 0 6px 15px rgba(22, 163, 74, 0.6); }
        }

.sz-center-container {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.sz-top-nav {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: row; 
    align-items: center;
    gap: 6px; 
}

.sz-top-nav .nav-link {
    font-family: 'Inter', sans-serif;
    font-size: 0.95rem;
    font-weight: 600;
    color: #64748B; 
    text-decoration: none;
    position: relative;
    padding: 10px 22px 14px 22px; 
    border-radius: 50px;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap; 
    z-index: 1;
    overflow: hidden;
}

.sz-top-nav .nav-link::before {
    content: '';
    position: absolute;
    inset: 0; 
    background: rgba(15, 23, 42, 0.05); 
    border-radius: 50px;
    transform: scale(0.5); 
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    z-index: -1;
}

.sz-top-nav .nav-link::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 50%;
    width: 6px;  
    height: 4px;
    background: #16a34a;  
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(22, 163, 74, 0.6);  
    transform: translateX(-50%) translateY(20px) scale(0);  
    opacity: 0;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);  
}

 .sz-top-nav .nav-link:hover,
.sz-top-nav .nav-link.active {
    color: #0F172A !important;  
    transform: translateY(-2px);  
}

 .sz-top-nav .nav-link:hover::before,
.sz-top-nav .nav-link.active::before {
    transform: scale(1);
    opacity: 1;
}

 .sz-top-nav .nav-link:hover::after,
.sz-top-nav .nav-link.active::after {
    transform: translateX(-50%) translateY(0) scale(1);
    width: 20px;  
    opacity: 1;
}

@media (max-width: 991.98px) {
    .sz-mobile-menu-btn {
        display: inline-flex !important;
    }

    .sz-mobile-nav-overlay {
        position: fixed;
        inset: 0;
        z-index: 9998;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.35s ease, visibility 0.35s ease;
    }

    .sz-mobile-nav-overlay.active {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
    }

    #szMobileNavLinks.sz-nav-links-container {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: auto !important;
        width: min(300px, 88vw) !important;
        max-width: 88vw !important;
        height: 100vh !important;
        height: 100dvh !important;
        margin: 0 !important;
        padding: 0 !important;
        z-index: 9999 !important;
        background: #ffffff !important;
        border: none !important;
        border-right: 1px solid #e2e8f0 !important;
        box-shadow: 8px 0 40px rgba(15, 23, 42, 0.15) !important;
        display: flex !important;
        flex-direction: column !important;
        transform: translateX(-100%) !important;
        visibility: visible !important;
        opacity: 1 !important;
        pointer-events: none;
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        overflow: hidden;
    }

    #szMobileNavLinks.sz-nav-links-container.active {
        transform: translateX(0) !important;
        pointer-events: auto;
    }

    body.sz-mobile-menu-open {
        overflow: hidden !important;
    }

    .sz-mobile-nav-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        flex-shrink: 0;
        background: #ffffff;
    }

    .sz-mobile-nav-title {
        font-family: 'Montserrat', 'Plus Jakarta Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 800;
        color: #0f172a;
    }

  .sz-mobile-nav-close {
        width: 44px;
        height: 44px;
        min-width: 44px;
        min-height: 44px;
        border: none;
        border-radius: 12px;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        cursor: pointer;
        transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
    }

    .sz-mobile-nav-close:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    .sz-mobile-nav-body {
        flex: 1;
        overflow-y: auto;
        padding: 12px 16px 24px;
        -webkit-overflow-scrolling: touch;
    }

    .sz-mobile-nav-list,
    .sz-mobile-nav-list li {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .sz-mobile-nav-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sz-mobile-nav-list .nav-link {
        display: block;
        padding: 14px 16px;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        text-decoration: none !important;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .sz-mobile-nav-list .nav-link:hover,
    .sz-mobile-nav-list .nav-link.active {
        background: #f0fdf4;
        color: #16a34a;
    }

    .sz-mobile-nav-actions,
    .sz-mobile-nav-auth {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sz-mobile-nav-actions a,
    .sz-mobile-nav-auth a {
        text-decoration: none !important;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .sz-mobile-action-teach { background: #ecfdf5; color: #059669; }
    .sz-mobile-action-learn { background: #eff6ff; color: #2563eb; }
    .sz-mobile-action-link {
        justify-content: flex-start;
        gap: 12px;
        padding: 12px 16px;
        color: #475569;
        background: #f8fafc;
    }

    .sz-mobile-auth-login { background: #f1f5f9; color: #0f172a; }
    .sz-mobile-auth-register {
        background: linear-gradient(135deg, #16a34a, #10b981);
        color: #fff;
    }
}

@media (min-width: 992px) {
    .sz-mobile-nav-overlay,
    #szMobileNavLinks.sz-nav-links-container {
        display: none !important;
    }
}

.skiptranslate > iframe {
    display: none !important;
    visibility: hidden !important;
}

html, body {
    top: 0px !important;
    margin-top: 0px !important;
    position: static !important;
    height: auto !important;
}

#goog-gt-tt, 
.goog-te-balloon-frame {
    display: none !important;
}

.goog-text-highlight {
    background-color: transparent !important;
    box-shadow: none !important;
}

#google_translate_element,
.goog-te-gadget {
    display: none !important;
}

.VIpgJd-ZVi9od-aZ2wEe-wOHMyf,
.VIpgJd-ZVi9od-aZ2wEe-wOHMyf-ti6hGc,
.goog-te-spinner-pos,
.goog-te-spinner-animation {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
    z-index: -9999 !important;
}

body > .VIpgJd-ZVi9od-aZ2wEe-wOHMyf {
    display: none !important;
}

font {
    display: contents !important; 
    background-color: transparent !important;
    box-shadow: none !important;
}

.goog-text-highlight {
    background-color: transparent !important;
    box-shadow: none !important;
}

.sz-btn-secondary {
    background: #F1F5F9 !important;
    color: #475569 !important;
    padding: 12px 24px !important;
    border-radius: 50px !important;
    font-weight: 700 !important;
    margin-right: 10px !important;
    border: none !important;
}

.sz-swal-popup.swal2-popup {
    max-width: 90vw !important;
    width: 420px !important;
    padding: 25px 20px !important;
    border-radius: 24px !important;
}

.sz-swal-popup .swal2-select {
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
    margin: 20px 0 !important;
    padding: 12px 15px !important;
    appearance: auto !important;
    -webkit-appearance: auto !important;
    font-size: 1rem !important;
}


.weather-main-container {
    padding-top: 100px;  
    position: relative;
    z-index: 1;
}
</style>

<div class="sz-nav-wrapper sz-navbar-scope" id="mainNavbarWrapper">
        <nav class="sz-nav-inner" id="mainNavbarInner">
      <a href="index.php" class="sz-brand notranslate" style="display: flex; align-items: center; white-space: nowrap; text-decoration: none;">
    <div class="icon-box" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
        <img src="assets/logo-192.png" alt="HarvestIQ Logo" class="brand-logo" style="width: 100%; height: 100%; object-fit: contain; filter: drop-shadow(0px 4px 8px rgba(22, 163, 74, 0.3)); transition: transform 0.3s ease;">
    </div>
    <div class="brand-text notranslate" style="white-space: nowrap;">Harvest<span>IQ</span></div>
</a>
        
        <div class="sz-center-container d-none d-lg-flex">
            <div class="sz-menu">
              <ul class="sz-top-nav d-none d-lg-flex">
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
        </li>
        <li class="nav-item">
            <a href="market_prices.php" class="nav-link <?php echo ($current_page == 'market_prices.php') ? 'active' : ''; ?>">Market Prices</a>
        </li>
        <li class="nav-item">
            <a href="weather.php" class="nav-link <?php echo ($current_page == 'weather.php') ? 'active' : ''; ?>">Weather & Advisory</a>
        </li>
         <li class="nav-item">
            <a href="crop_recommendation.php" class="nav-link <?php echo ($current_page == 'crop_recommendation.php') ? 'active' : ''; ?>">Crop Inventory</a>
        </li>
    <?php else: ?>
        <li class="nav-item">
            <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
        </li>
       <li class="nav-item">
            <a href="index.php#features" class="nav-link">Features</a>
        </li>
        <li class="nav-item">
            <a href="index.php#market" class="nav-link">Market Live</a>
        </li>
    <?php endif; ?>

</ul>
        </div>
        </div>
         
<div class="sz-divider"></div>
<div class="d-flex align-items-center" style="gap: 20px; z-index: 5;">

    <button type="button" class="hiq-theme-toggle d-none d-sm-flex" data-hiq-theme-toggle aria-label="Switch theme" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 50px; color: #fff; cursor: pointer;">
        <i class="fa-solid fa-moon"></i>
    </button>

    <?php if ($is_logged_in): ?>
        <div class="sz-action-hub" id="szHubContainer">
            <button class="btn-sz-widget" id="szCreateBtn">
                <div class="widget-icon-circle"><i class="fa-solid fa-seedling"></i></div>
                <span class="d-none d-sm-block">Quick Tools</span>
            </button>
            
            <div class="sz-dropdown-menu" id="szCreateMenu">
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--sz-text-muted); padding-left: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Farmer Actions</span>
                <div style="height:10px;"></div>
                
                <a href="weather.php" class="sz-action-item action-teach">
                    <div class="sz-action-icon"><i class="fa-solid fa-cloud-sun-rain"></i></div>
                    <div class="sz-action-text">
                        <strong>Check Weather</strong>
                        <span>Get local advisory updates</span>
                    </div>
                </a>
                
                <a href="market_prices.php" class="sz-action-item action-learn">
                    <div class="sz-action-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <div class="sz-action-text">
                        <strong>View Market</strong>
                        <span>Track live crop prices</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="sz-divider d-none d-sm-block"></div>

        <div class="profile-dropdown-container d-none d-sm-block position-relative">
            <button class="sz-profile-btn" id="profileDropdownBtn">
                <?php echo $user_initial; ?>
            </button>
            
            <div class="sz-dropdown-menu" id="profileDropdownMenu" style="width: 250px;">
                <div style="padding: 10px; border-bottom: 1px solid var(--sz-border-soft); margin-bottom: 10px;">
                    <strong style="color: var(--sz-text-dark); font-size: 1.3rem;"><?php echo htmlspecialchars(ucwords($display_name)); ?></strong>
                    <?php if ($is_verified == 1): ?><i class="fa-solid fa-circle-check text-primary ms-1"></i><?php endif; ?>
                </div>
                
                <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/admin_dashboard.php" class="sz-action-item" style="background: #fff7ed; border: 1px solid #fed7aa; margin-bottom: 10px;">
                        <div class="sz-action-icon" style="width: 30px; height: 30px; background: #ffedd5; color: #ea580c;"><i class="fa-solid fa-user-shield"></i></div>
                        <span style="color: #ea580c; font-weight: 800;">Admin Panel</span>
                    </a>
                <?php endif; ?>

                <a href="javascript:void(0);" class="sz-action-item" onclick="changeLanguage()">
                    <div class="sz-action-icon" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a;"><i class="fa-solid fa-globe"></i></div>
                    <div style="display: flex; flex-direction: column;">
                        <span style="color: #0f172a; font-weight: 600; line-height: 1.2;">Language</span>
                        <span style="font-size: 11px; color: #64748b;">Change display language</span>
                    </div>
                </a>

                <a href="profile.php" class="sz-action-item">
                    <div class="sz-action-icon" style="width: 30px; height: 30px; background: #f0fdf4; color: #16a34a;"><i class="fa-solid fa-user"></i></div>
                    <span style="color: var(--sz-text-dark); font-weight: 600;">My Profile</span>
                </a>

                <a href="dashboard.php" class="sz-action-item">
                    <div class="sz-action-icon" style="width: 30px; height: 30px; background: #eff6ff; color: #2563eb;"><i class="fa-solid fa-chart-simple"></i></div>
                    <span style="color: var(--sz-text-dark); font-weight: 600;">My Dashboard</span>
                </a>

            

                <hr class="dropdown-divider" style="margin: 8px 0; border-color: var(--sz-border);">

                <a href="javascript:void(0)" onclick="openGlobalLogout()" class="sz-action-item" style="margin-top: 5px;">
                    <div class="sz-action-icon" style="width: 30px; height: 30px; background: #fee2e2; color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i></div>
                    <span style="color: #ef4444; font-weight: 600;">Sign out</span>
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="javascript:void(0)" onclick="openAuthModal('loginModal')" class="btn-login d-none d-sm-block text-decoration-none">Login</a>
        <a href="javascript:void(0)" onclick="openAuthModal('signupModal')" class="btn-register d-none d-sm-block text-decoration-none">Join Free</a>
    <?php endif; ?>

   <button type="button" class="sz-mobile-menu-btn d-lg-none" id="szMobileMenuBtn" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="szMobileNavLinks">
    <i class="fa-solid fa-bars-staggered sz-icon-menu" aria-hidden="true"></i>
</button>
</div>

    </nav>
</div>

<div class="sz-mobile-nav-overlay" id="szMobileNavOverlay" aria-hidden="true"></div>
<aside class="sz-nav-links-container" id="szMobileNavLinks" aria-hidden="true">
        <div class="sz-mobile-nav-header">
            <span class="sz-mobile-nav-title">Menu</span>
            <button type="button" class="sz-mobile-nav-close" id="szMobileNavClose" aria-label="Close menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="sz-mobile-nav-body">
            <ul class="sz-mobile-nav-list">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a href="market_prices.php" class="nav-link <?php echo ($current_page == 'market_prices.php') ? 'active' : ''; ?>">Market Prices</a>
                    </li>
                    <li class="nav-item">
                        <a href="weather.php" class="nav-link <?php echo ($current_page == 'weather.php') ? 'active' : ''; ?>">Advisory</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php#features" class="nav-link">Features</a>
                    </li>
                    <li class="nav-item">
                        <a href="index.php#market" class="nav-link">Market Live</a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="sz-mobile-nav-theme" style="margin-top:16px;padding-top:16px;border-top:1px solid var(--sz-border-soft);display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;font-size:0.9rem;color:var(--sz-text-dark);">Appearance</span>
                <button type="button" class="hiq-theme-toggle" data-hiq-theme-toggle aria-label="Switch theme">
                    <span class="hiq-theme-toggle-track"></span>
                    <span class="hiq-theme-toggle-thumb">
                        <i class="fa-solid fa-sun icon-sun"></i>
                        <i class="fa-solid fa-moon icon-moon"></i>
                    </span>
                </button>
            </div>

            
            <?php if ($is_logged_in): ?>
                <div class="sz-mobile-nav-actions">
                    <a href="weather.php" class="sz-mobile-action-btn sz-mobile-action-teach">
                        <i class="fa-solid fa-cloud-sun-rain"></i> Check Weather
                    </a>
                    <a href="market_prices.php" class="sz-mobile-action-btn sz-mobile-action-learn">
                        <i class="fa-solid fa-chart-line"></i> View Market
                    </a>
                    <a href="dashboard.php" class="sz-mobile-action-link">
                        <i class="fa-solid fa-chart-simple"></i> My Dashboard
                    </a>
                    <a href="profile.php" class="sz-mobile-action-link">
                        <i class="fa-solid fa-user"></i> My Profile
                    </a>
                </div>
            <?php else: ?>
                <div class="sz-mobile-nav-auth">
                    <a href="javascript:void(0)" onclick="toggleMobileMenu(true); openAuthModal('loginModal');" class="sz-mobile-auth-login">Login</a>
                    <a href="javascript:void(0)" onclick="toggleMobileMenu(true); openAuthModal('signupModal');" class="sz-mobile-auth-register">Join Free</a>
                </div>
            <?php endif; ?>
        </nav>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const wrapper = document.getElementById("mainNavbarWrapper");
        
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) wrapper.classList.add("scrolled");
            else wrapper.classList.remove("scrolled");
        });

        const szCreateBtn = document.getElementById("szCreateBtn");
        const szCreateMenu = document.getElementById("szCreateMenu");
        const szHubContainer = document.getElementById("szHubContainer");

        if (szCreateBtn && szCreateMenu) {
            szCreateBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                szCreateBtn.classList.toggle("active");
                szCreateMenu.classList.toggle("active");
            });
        }

        const profileBtn = document.getElementById("profileDropdownBtn");
        const profileMenu = document.getElementById("profileDropdownMenu");

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle("active");
            });
        }

        document.addEventListener("click", function(e) {
            if (szHubContainer && !szHubContainer.contains(e.target)) {
                szCreateBtn.classList.remove("active");
                szCreateMenu.classList.remove("active");
            }
            if (profileBtn && !profileBtn.contains(e.target) && profileMenu && !profileMenu.contains(e.target)) {
                profileMenu.classList.remove("active");
            }
        });

        const mobileMenuBtn = document.getElementById("szMobileMenuBtn");
        const mobileNavClose = document.getElementById("szMobileNavClose");
        const mobileNavOverlay = document.getElementById("szMobileNavOverlay");

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                toggleMobileMenu();
            });
        }
        if (mobileNavClose) {
            mobileNavClose.addEventListener("click", function() {
                toggleMobileMenu(true);
            });
        }
        if (mobileNavOverlay) {
            mobileNavOverlay.addEventListener("click", function() {
                toggleMobileMenu(true);
            });
        }

        document.querySelectorAll("#szMobileNavLinks .nav-link, #szMobileNavLinks .sz-mobile-action-btn, #szMobileNavLinks .sz-mobile-action-link").forEach(function(link) {
            link.addEventListener("click", function() {
                toggleMobileMenu(true);
            });
        });

        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                toggleMobileMenu(true);
            }
        });
    });

    function toggleMobileMenu(forceClose) {
        const panel = document.getElementById("szMobileNavLinks");
        const overlay = document.getElementById("szMobileNavOverlay");
        const btn = document.getElementById("szMobileMenuBtn");

        if (!panel) return;

        const isOpen = panel.classList.contains("active");
        const shouldOpen = forceClose === true ? false : (forceClose === false ? true : !isOpen);

        panel.classList.toggle("active", shouldOpen);
        if (overlay) overlay.classList.toggle("active", shouldOpen);
        if (btn) {
            btn.classList.toggle("active", shouldOpen);
            btn.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
        }

        panel.setAttribute("aria-hidden", shouldOpen ? "false" : "true");
        if (overlay) overlay.setAttribute("aria-hidden", shouldOpen ? "false" : "true");

        document.body.classList.toggle("sz-mobile-menu-open", shouldOpen);
    }

function toggleScopeMenu() {
    document.getElementById('scopeMenu').classList.toggle('active');
}
</script>

<style>
    .sz-logout-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        z-index: 999999; 
        display: flex; align-items: center; justify-content: center;
        opacity: 0; visibility: hidden; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .sz-logout-overlay.active { opacity: 1; visibility: visible; }

    .sz-logout-popup {
        background: #ffffff; width: 100%; max-width: 400px;
        padding: 40px 30px; border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3); text-align: center;
        transform: scale(0.90) translateY(20px); transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .sz-logout-overlay.active .sz-logout-popup { transform: scale(1) translateY(0); }

    .sz-logout-icon-wrapper {
        width: 80px; height: 80px; background: #FEE2E2; color: #EF4444;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; margin: 0 auto 20px auto; box-shadow: 0 10px 25px rgba(239, 68, 68, 0.2);
    }
    .sz-logout-popup h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800; font-size: 1.5rem; color: #0F172A; margin-bottom: 10px; }
    .sz-logout-popup p { color: #64748B; font-size: 0.95rem; margin-bottom: 30px; line-height: 1.5; font-weight: 500; }
    .sz-logout-actions { display: flex; gap: 15px; }

    .sz-btn-cancel { flex: 1; padding: 14px; background: #F1F5F9; color: #475569; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
    .sz-btn-cancel:hover { background: #E2E8F0; color: #0F172A; }

    .sz-btn-confirm { flex: 1; padding: 14px; background: #EF4444; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; box-shadow: 0 8px 20px rgba(239, 68, 68, 0.25); transition: 0.3s; display: flex; justify-content: center; align-items: center; gap: 8px; }
    .sz-btn-confirm:hover { background: #DC2626; transform: translateY(-2px); box-shadow: 0 12px 25px rgba(239, 68, 68, 0.35); }
</style>

<div class="sz-logout-overlay" id="globalLogoutModal">
    <div class="sz-logout-popup">
        <div class="sz-logout-icon-wrapper">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
        <h3>Leaving so soon?</h3>
        <p>Are you sure you want to sign out of your HarvestIQ account?</p>
        <div class="sz-logout-actions">
            <button class="sz-btn-cancel" onclick="closeGlobalLogout()">Cancel</button>
            <button class="sz-btn-confirm" onclick="executeGlobalLogout(this)">Yes, Sign out</button>
        </div>
    </div>
</div>

<script>
    function openGlobalLogout() {
        const modal = document.getElementById('globalLogoutModal');
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => { modal.classList.add('active'); }, 10);
            document.body.style.overflow = "hidden"; 
        }
    }

    function closeGlobalLogout() {
        const modal = document.getElementById('globalLogoutModal');
        if (modal) {
            modal.classList.remove('active');
            setTimeout(() => { modal.style.display = 'none'; }, 400);
            document.body.style.overflow = "auto"; 
        }
    }

   function executeGlobalLogout(btn) {
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Signing out...';
        btn.style.pointerEvents = 'none';
        
         document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + location.hostname + ";";
        
          localStorage.removeItem('googtrans');
        sessionStorage.removeItem('googtrans');

        setTimeout(() => {
            window.location.href = 'logout.php';
        }, 600);
    }
</script>
<script src="assets/js/theme.js?v=3.0"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
    // 1. Google Translate Init
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en', 
            autoDisplay: false
        }, 'google_translate_element');
    }

    // 2. Language Change Modal
    function changeLanguage() {
        document.querySelectorAll('i').forEach(icon => icon.classList.add('notranslate'));

        const translateSelect = document.querySelector('.goog-te-combo');
        
        if (!translateSelect || translateSelect.options.length === 0) {
            Swal.fire({
                title: 'Loading Engine...', 
                text: 'Translation engine is connecting. Please try again in a few seconds.', 
                icon: 'info', 
                customClass: {popup: 'sz-swal-popup', confirmButton: 'sz-btn-primary'}, 
                buttonsStyling: false
            });
            return;
        }

        const nativeNames = {
            'bn': 'বাংলা (Bengali)', 'hi': 'हिन्दी (Hindi)', 'as': 'অসমীয়া (Assamese)',
            'or': 'ଓଡ଼ିଆ (Odia)', 'ta': 'தமிழ் (Tamil)', 'te': 'తెలుగు (Telugu)',
            'mr': 'मराठी (Marathi)', 'gu': 'ગુજરાતી (Gujarati)', 'kn': 'ಕನ್ನಡ (Kannada)',
            'ml': 'മലയാളം (Malayalam)', 'pa': 'ਪੰਜਾਬੀ (Punjabi)', 'ur': 'اردو (Urdu)',
            'sa': 'संस्कृतम् (Sanskrit)', 'ne': 'नेपाली (Nepali)', 'en': 'English'
        };
        
        let dynamicLangOptions = {};
        for (let i = 0; i < translateSelect.options.length; i++) {
            let option = translateSelect.options[i];
            if (option.value) { 
                dynamicLangOptions[option.value] = nativeNames[option.value] || option.text;
            }
        }

        Swal.fire({
            title: '<div style="font-size: 1.3rem; font-weight: 800;">Select Language</div>',
            input: 'select',
            inputOptions: dynamicLangOptions, 
            inputPlaceholder: 'Search or choose a language...',
            showCancelButton: true,
            confirmButtonText: 'Translate Now',
            cancelButtonText: 'Cancel',
            showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            customClass: { 
                popup: 'sz-swal-popup notranslate', 
                confirmButton: 'sz-btn-primary',
                cancelButton: 'sz-btn-secondary' 
            },
            buttonsStyling: false,
            inputAttributes: {
                style: 'padding: 12px; border-radius: 12px; border: 1px solid #E2E8F0; width: 100% !important; box-sizing: border-box !important;'
            }
        }).then((res) => {
            if(res.isConfirmed && res.value) {
                translateSelect.value = res.value;
                translateSelect.dispatchEvent(new Event('change'));
                
                Swal.fire({
                    icon: 'success',
                    title: '<div style="font-size: 1.2rem; font-weight: 800;">Language Updated!</div>',
                    text: 'Your page has been successfully translated.',
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'sz-swal-popup notranslate' }
                });
            }
        });
    }
</script>

<script>
    // 3. THEME TOGGLE LOGIC
    document.addEventListener("DOMContentLoaded", function() {
        const themeToggles = document.querySelectorAll('[data-hiq-theme-toggle]');
        const htmlElement = document.documentElement;

        function updateToggleIcons(theme) {
            themeToggles.forEach(btn => {
                const icon = btn.querySelector('i.fa-solid:not(.icon-sun):not(.icon-moon)'); 
                if (icon) {
                    if (theme === 'dark') {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                    }
                }
            });
        }

        let currentTheme = localStorage.getItem('harvestiq-theme') || 'light';
        updateToggleIcons(currentTheme);

        themeToggles.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); 
                let theme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                htmlElement.setAttribute('data-theme', theme);
                localStorage.setItem('harvestiq-theme', theme); 
                updateToggleIcons(theme);
            });
        });
    });
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // যতগুলো থিম টগল বাটন আছে সব খুঁজে বের করা
    const themeToggles = document.querySelectorAll('[data-hiq-theme-toggle]');
    const htmlElement = document.documentElement;

    // ডেস্কটপ আইকন (চাঁদ/সূর্য) আপডেট করার ফাংশন
    function updateToggleIcons(theme) {
        themeToggles.forEach(btn => {
            // শুধু ডেস্কটপ আইকনটি টার্গেট করা
            const icon = btn.querySelector('i.fa-solid:not(.icon-sun):not(.icon-moon)'); 
            if (icon) {
                if (theme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            }
        });
    }

    // পেজ লোড হওয়ার সময় বর্তমান থিম চেক করা
    let currentTheme = localStorage.getItem('harvestiq-theme') || 'light';
    updateToggleIcons(currentTheme);

    // বাটনে ক্লিক করলে থিম চেঞ্জ হওয়া
    themeToggles.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // ডিফল্ট কাজ বন্ধ করা
            let theme = htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', theme);
            localStorage.setItem('harvestiq-theme', theme); // সেভ করে রাখা
            updateToggleIcons(theme);
        });
    });
});
</script>

    
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('./sw.js')
                .then(reg => console.log('Offline system active!'))
                .catch(err => console.log('Offline system failed:', err));
        });
    }
</script>