<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather Alert Centre — Admin | HarvestIQ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --green: #10b981;
      --green-d: #059669;
      --red: #ef4444;
      --red-d: #dc2626;
      --navy: #0f172a;
      --slate: #475569;
      --glass: rgba(255, 255, 255, 0.72);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #d4f5e9 0%, #bfdbfe 50%, #e0e7ff 100%);
      background-size: 300% 300%;
      animation: bgShift 20s ease infinite;
      min-height: 100vh;
      color: var(--navy);
      overflow-x: hidden;
    }

    @keyframes bgShift {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    .main-content {
      margin-left: 280px;
      min-height: 100vh;
      padding: 32px 36px;
    }

    #toastWrap {
      position: fixed;
      top: 24px;
      right: 24px;
      z-index: 99999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .t-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 20px;
      border-radius: 16px;
      font-weight: 600;
      font-size: .93rem;
      color: #fff;
      min-width: 280px;
      box-shadow: 0 12px 35px rgba(0, 0, 0, .18);
      animation: tIn .4s cubic-bezier(.34, 1.56, .64, 1) forwards;
    }

    .t-item.hide {
      animation: tOut .3s ease forwards;
    }

    .t-item.s {
      background: linear-gradient(135deg, #10b981, #059669);
    }

    .t-item.e {
      background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .t-item.w {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    @keyframes tIn {
      from {
        opacity: 0;
        transform: translateX(60px) scale(.85);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    @keyframes tOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
        transform: translateX(60px);
      }
    }

    .page-header {
      margin-bottom: 32px;
    }

    .page-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(255, 255, 255, .85);
      border-radius: 50px;
      padding: 5px 16px;
      font-weight: 700;
      font-size: .78rem;
      letter-spacing: .8px;
      color: var(--slate);
      margin-bottom: 10px;
    }

    .page-header h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: -1.5px;
      color: var(--navy);
      margin-bottom: 4px;
    }

    .emergency-banner {
      background: linear-gradient(90deg, #ef4444, #b91c1c);
      color: #fff;
      font-weight: 800;
      padding: 12px 0;
      overflow: hidden;
      white-space: nowrap;
      display: none;
      border-radius: 16px;
      margin-bottom: 24px;
      box-shadow: 0 6px 20px rgba(239, 68, 68, .35);
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }

    #alertMarquee {
      display: inline-block;
      white-space: nowrap;
      padding-left: 100%;
      animation: marquee 30s linear infinite;
      font-size: 1rem;
    }

    @keyframes marquee {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-100%);
      }
    }

    .g-card {
      background: var(--glass);
      backdrop-filter: blur(28px);
      -webkit-backdrop-filter: blur(28px);
      border: 1.5px solid rgba(255, 255, 255, .8);
      border-radius: 24px;
      box-shadow: 0 16px 45px rgba(0, 0, 0, .07);
      padding: 28px;
    }

    .stat-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--glass);
      backdrop-filter: blur(20px);
      border: 1.5px solid rgba(255, 255, 255, .8);
      border-radius: 20px;
      padding: 22px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
      transition: transform .25s ease, box-shadow .25s ease;
    }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 16px 36px rgba(0, 0, 0, .1);
    }

    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }

    .stat-val {
      font-size: 1.9rem;
      font-weight: 800;
      color: var(--navy);
      line-height: 1;
    }

    .stat-lbl {
      font-size: .78rem;
      font-weight: 700;
      color: var(--slate);
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-top: 3px;
    }

    .two-col {
      display: grid;
      grid-template-columns: 420px 1fr;
      gap: 24px;
      align-items: start;
    }

    .compose-panel {
      background: rgba(255, 255, 255, .95);
      backdrop-filter: blur(20px);
      border: 1.5px solid rgba(255, 255, 255, .9);
      border-radius: 24px;
      box-shadow: 0 16px 45px rgba(0, 0, 0, .07);
      overflow: hidden;
    }

    .compose-header {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      padding: 22px 26px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .compose-header .ch-icon {
      width: 44px;
      height: 44px;
      background: rgba(239, 68, 68, .2);
      border: 1.5px solid rgba(239, 68, 68, .4);
      border-radius: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: #fca5a5;
    }

    .compose-body {
      padding: 26px;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 5px 14px;
      border-radius: 50px;
      font-weight: 700;
      font-size: .78rem;
    }

    .status-pill.active {
      background: rgba(239, 68, 68, .12);
      color: #b91c1c;
      border: 1.5px solid rgba(239, 68, 68, .25);
    }

    .status-pill.inactive {
      background: rgba(100, 116, 139, .1);
      color: #475569;
      border: 1.5px solid rgba(100, 116, 139, .2);
    }

    .pulse-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #ef4444;
      animation: dotPulse 1.4s ease-in-out infinite;
    }

    @keyframes dotPulse {

      0%,
      100% {
        opacity: 1;
        transform: scale(1);
      }

      50% {
        opacity: .35;
        transform: scale(.65);
      }
    }

    .live-alert-container {
      background: linear-gradient(135deg, rgba(254, 226, 226, .5), rgba(254, 202, 202, .3));
      border: 1.5px solid rgba(239, 68, 68, .2);
      border-radius: 16px;
      padding: 16px;
      margin-bottom: 22px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .no-alert-box {
      background: linear-gradient(135deg, rgba(209, 250, 229, .7), rgba(167, 243, 208, .4));
      border: 1.5px solid rgba(16, 185, 129, .25);
      border-radius: 16px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 22px;
    }

    .f-label {
      font-weight: 700;
      font-size: .82rem;
      color: var(--slate);
      text-transform: uppercase;
      letter-spacing: .8px;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 7px;
    }

    .f-input {
      background: rgba(255, 255, 255, .9);
      border: 1.5px solid rgba(203, 213, 225, .6);
      border-radius: 12px;
      padding: 12px 16px;
      font-family: 'Outfit', sans-serif;
      font-size: .95rem;
      color: var(--navy);
      transition: all .25s ease;
      width: 100%;
    }

    .f-input:focus {
      outline: none;
      border-color: var(--green);
      box-shadow: 0 0 0 4px rgba(16, 185, 129, .12);
      background: #fff;
    }

    textarea.f-input {
      resize: vertical;
      min-height: 90px;
    }

    select.f-input {
      cursor: pointer;
    }

    .btn-broadcast {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 13px 22px;
      font-weight: 700;
      font-size: .95rem;
      font-family: 'Outfit', sans-serif;
      cursor: pointer;
      transition: all .3s ease;
      display: flex;
      align-items: center;
      gap: 9px;
      box-shadow: 0 8px 24px rgba(239, 68, 68, .35);
      flex: 1;
    }

    .btn-broadcast:hover {
      transform: translateY(-2px);
      box-shadow: 0 14px 32px rgba(239, 68, 68, .45);
    }

    .btn-refresh-sm {
      background: rgba(255, 255, 255, .9);
      border: 1.5px solid rgba(203, 213, 225, .6);
      border-radius: 12px;
      padding: 13px 16px;
      font-size: 1rem;
      cursor: pointer;
      color: var(--slate);
      transition: all .25s ease;
    }

    .btn-refresh-sm:hover {
      background: #fff;
      border-color: var(--green);
      color: var(--green);
    }

    .area-tags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 10px;
    }

    .area-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(16, 185, 129, .1);
      border: 1.5px solid rgba(16, 185, 129, .25);
      color: #047857;
      border-radius: 50px;
      padding: 4px 12px;
      font-size: .8rem;
      font-weight: 700;
      cursor: pointer;
      transition: all .2s ease;
    }

    .area-tag:hover {
      background: rgba(16, 185, 129, .2);
    }

    .area-tag .rm {
      color: #dc2626;
      font-weight: 900;
      font-size: .85rem;
      margin-left: 2px;
    }

    .history-panel {
      background: rgba(255, 255, 255, .95);
      backdrop-filter: blur(20px);
      border: 1.5px solid rgba(255, 255, 255, .9);
      border-radius: 24px;
      box-shadow: 0 16px 45px rgba(0, 0, 0, .07);
      overflow: hidden;
    }

    .history-header {
      padding: 20px 26px;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1.5px solid rgba(226, 232, 240, .6);
      background: rgba(248, 250, 252, .8);
    }

    .history-header h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1rem;
      margin: 0;
      color: var(--navy);
    }

    .alert-table {
      width: 100%;
      border-collapse: collapse;
    }

    .alert-table thead th {
      background: rgba(241, 245, 249, .9);
      padding: 11px 18px;
      font-size: .72rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: var(--slate);
      border-bottom: 1.5px solid rgba(203, 213, 225, .4);
      white-space: nowrap;
    }

    .alert-table tbody tr {
      border-bottom: 1px solid rgba(226, 232, 240, .5);
      transition: background .2s ease;
    }

    .alert-table tbody tr:last-child {
      border-bottom: none;
    }

    .alert-table tbody tr:hover {
      background: rgba(248, 250, 252, .8);
    }

    .alert-table tbody td {
      padding: 14px 18px;
      font-size: .88rem;
      vertical-align: middle;
    }

    .msg-text {
      color: var(--navy);
      font-weight: 500;
      max-width: 320px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      line-height: 1.45;
    }

    .area-chip {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: rgba(99, 102, 241, .1);
      color: #4338ca;
      border: 1px solid rgba(99, 102, 241, .2);
      border-radius: 50px;
      padding: 3px 10px;
      font-size: .72rem;
      font-weight: 700;
      margin: 2px;
    }

    .all-chip {
      background: rgba(245, 158, 11, .1);
      color: #b45309;
      border-color: rgba(245, 158, 11, .25);
    }

    .tbl-badge {
      padding: 4px 12px;
      border-radius: 50px;
      font-size: .72rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      white-space: nowrap;
    }

    .b-active {
      background: rgba(239, 68, 68, .1);
      color: #b91c1c;
      border: 1px solid rgba(239, 68, 68, .2);
    }

    .b-inactive {
      background: rgba(100, 116, 139, .08);
      color: #64748b;
      border: 1px solid rgba(100, 116, 139, .15);
    }

    .b-expired {
      background: rgba(245, 158, 11, .1);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, .2);
    }

    .date-txt {
      font-size: .75rem;
      color: var(--slate);
      white-space: nowrap;
    }

    .act-btn {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      border: 1.5px solid;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: .85rem;
      transition: all .2s ease;
      background: transparent;
    }

    .act-activate {
      border-color: rgba(16, 185, 129, .3);
      color: #10b981;
    }

    .act-activate:hover {
      background: rgba(16, 185, 129, .12);
      transform: scale(1.1);
    }

    .act-deactivate {
      border-color: rgba(239, 68, 68, .3);
      color: #ef4444;
    }

    .act-deactivate:hover {
      background: rgba(239, 68, 68, .12);
      transform: scale(1.1);
    }

    .act-delete {
      border-color: rgba(100, 116, 139, .25);
      color: #94a3b8;
    }

    .act-delete:hover {
      border-color: rgba(239, 68, 68, .3);
      color: #ef4444;
      background: rgba(239, 68, 68, .06);
      transform: scale(1.1);
    }

    .actions-cell {
      display: flex;
      gap: 6px;
      align-items: center;
    }

    .empty-state {
      text-align: center;
      padding: 48px 20px;
      color: var(--slate);
    }

    .empty-state i {
      font-size: 2.8rem;
      opacity: .25;
      margin-bottom: 12px;
      display: block;
    }

    .empty-state p {
      font-weight: 600;
      font-size: .95rem;
      margin: 0;
    }

    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, .45);
      backdrop-filter: blur(6px);
      z-index: 9998;
      display: none;
      align-items: center;
      justify-content: center;
    }

    .modal-overlay.show {
      display: flex;
    }

    .modal-box {
      background: #fff;
      border-radius: 24px;
      padding: 32px;
      max-width: 420px;
      width: 90%;
      box-shadow: 0 32px 80px rgba(0, 0, 0, .2);
      animation: modalIn .3s cubic-bezier(.34, 1.56, .64, 1);
    }

    @keyframes modalIn {
      from {
        opacity: 0;
        transform: scale(.85)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .modal-icon {
      width: 60px;
      height: 60px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      margin: 0 auto 18px;
    }

    .modal-box h5 {
      font-family: 'Poppins', sans-serif;
      font-weight: 800;
      font-size: 1.1rem;
      text-align: center;
      margin-bottom: 8px;
    }

    .modal-box p {
      color: var(--slate);
      font-size: .93rem;
      text-align: center;
      margin-bottom: 24px;
    }

    .modal-btns {
      display: flex;
      gap: 12px;
    }

    .modal-btns button {
      flex: 1;
      padding: 13px;
      border-radius: 12px;
      font-weight: 700;
      font-size: .95rem;
      cursor: pointer;
      border: none;
      font-family: 'Outfit', sans-serif;
      transition: all .2s;
    }

    .btn-cancel {
      background: #f1f5f9;
      color: var(--slate);
    }

    .btn-cancel:hover {
      background: #e2e8f0;
    }

    .btn-confirm-del {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff;
      box-shadow: 0 8px 20px rgba(239, 68, 68, .3);
    }

    .btn-confirm-del:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px rgba(239, 68, 68, .4);
    }

    .table-scroll {
      overflow-x: auto;
    }

    .table-scroll::-webkit-scrollbar {
      height: 5px;
    }

    .table-scroll::-webkit-scrollbar-track {
      background: transparent;
    }

    .table-scroll::-webkit-scrollbar-thumb {
      background: rgba(148, 163, 184, .4);
      border-radius: 10px;
    }

    @keyframes fadeUp {
      from {
        opacity: 0;
        transform: translateY(20px)
      }

      to {
        opacity: 1;
        transform: none
      }
    }

    .animate-in {
      animation: fadeUp .6s ease forwards;
    }
  </style>
</head>

<body>
  <?php include "admin_sidebar.php"; ?>
  <div class="main-content animate-in">
    <div id="toastWrap"></div>
    <div class="emergency-banner" id="alertBanner">
      <div id="alertMarquee"></div>
    </div>
    <div class="page-header">
      <div class="page-badge"><i class="fa-solid fa-tower-broadcast text-danger"></i> Admin Control</div>
      <h1>Weather Alert Centre</h1>
      <p class="text-secondary fw-medium" style="font-size:.97rem;">Broadcast area-based emergency alerts to farmers &amp; manage alert history.</p>
    </div>
    <div class="stat-row" id="statRow">
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,.1);">
          <i class="fa-solid fa-bell" style="color:#ef4444;"></i>
        </div>
        <div>
          <div class="stat-val" id="statTotal">—</div>
          <div class="stat-lbl">Total Alerts</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,.1);">
          <i class="fa-solid fa-satellite-dish" style="color:#dc2626;"></i>
        </div>
        <div>
          <div class="stat-val" id="statActive">—</div>
          <div class="stat-lbl">Live Active</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(100,116,139,.1);">
          <i class="fa-solid fa-ban" style="color:#64748b;"></i>
        </div>
        <div>
          <div class="stat-val" id="statInactive">—</div>
          <div class="stat-lbl">Inactive</div>
        </div>
      </div>
    </div>
    <div class="two-col">
      <div class="compose-panel">
        <div class="compose-header">
          <div class="ch-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div class="flex-grow-1">
            <div class="text-white fw-bold" style="font-size:1rem;">Alert Control Panel</div>
            <div style="color:rgba(255,255,255,.5);font-size:.8rem;">Broadcast emergency messages</div>
          </div>
          <span id="alertStatusBadge" class="status-pill inactive">
            <span class="pulse-dot" style="background:#64748b;animation:none;"></span> Loading
          </span>
        </div>
        <div class="compose-body">
          <div id="activeAlertBox" class="live-alert-container d-none">
          </div>
          <div id="noAlertBox" class="no-alert-box">
            <i class="fa-solid fa-circle-check text-success"></i>
            <span class="fw-medium" style="color:#065f46;font-size:.9rem;">No active alert — farmers see normal weather.</span>
          </div>
          <div class="mb-3">
            <label class="f-label">
              <i class="fa-solid fa-map-location-dot text-indigo-500" style="color:#6366f1;"></i> Target Area
            </label>
            <div class="d-flex gap-2">
              <select id="areaSelect" class="f-input">
                <option value="">— Select district / area —</option>
                <optgroup label="West Bengal">
                  <option value="All WB">All West Bengal</option>
                  <option value="Kolkata">Kolkata</option>
                  <option value="Burdwan">Burdwan (Purba & Paschim)</option>
                  <option value="Nadia">Nadia</option>
                  <option value="Murshidabad">Murshidabad</option>
                  <option value="Malda">Malda</option>
                  <option value="North 24 Parganas">North 24 Parganas</option>
                  <option value="South 24 Parganas">South 24 Parganas</option>
                  <option value="Hooghly">Hooghly</option>
                  <option value="Howrah">Howrah</option>
                  <option value="Purba Medinipur">Purba Medinipur (Coastal)</option>
                  <option value="Paschim Medinipur">Paschim Medinipur</option>
                  <option value="Birbhum">Birbhum</option>
                  <option value="Bankura">Bankura</option>
                  <option value="Purulia">Purulia</option>
                  <option value="Jalpaiguri">Jalpaiguri</option>
                  <option value="Darjeeling">Darjeeling</option>
                  <option value="Cooch Behar">Cooch Behar</option>
                  <option value="Alipurduar">Alipurduar</option>
                  <option value="Dakshin Dinajpur">Dakshin Dinajpur</option>
                  <option value="Uttar Dinajpur">Uttar Dinajpur</option>
                </optgroup>
                <optgroup label="Other Regions">
                  <option value="Dhaka">Dhaka, Bangladesh</option>
                  <option value="All India">Pan India</option>
                </optgroup>
              </select>
              <button onclick="addArea()" class="btn-refresh-sm" title="Add Area" style="white-space:nowrap;">
                <i class="fa-solid fa-plus"></i>
              </button>
            </div>
            <div class="area-tags" id="areaTags"></div>
            <input type="hidden" id="selectedAreas" value="">
          </div>
          <div class="mb-3">
            <label class="f-label">
              <i class="fa-solid fa-bullhorn text-danger"></i> Alert Message
            </label>
            <textarea id="alertMessage" class="f-input" rows="4" placeholder="e.g. Cyclone WARNING: Farmers in coastal WB districts advised to suspend all field operations until further notice."></textarea>
          </div>
          <div class="mb-4">
            <label class="f-label">
              <i class="fa-solid fa-clock text-warning"></i> Expires At
              <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;">(optional)</span>
            </label>
            <input type="datetime-local" id="alertExpiry" class="f-input">
          </div>
          <div class="d-flex gap-2">
            <button class="btn-broadcast" onclick="sendAlert()">
              <i class="fa-solid fa-bullhorn"></i> Broadcast Alert
            </button>
            <button class="btn-refresh-sm" onclick="loadAlerts()" title="Refresh">
              <i class="fa-solid fa-rotate-right"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="history-panel">
        <div class="history-header">
          <div style="width:38px;height:38px;border-radius:11px;background:rgba(239,68,68,.1);display:flex;align-items:center;justify-content:center;color:#ef4444;font-size:1rem;">
            <i class="fa-solid fa-history"></i>
          </div>
          <div class="flex-grow-1">
            <h5>Alert History</h5>
            <div style="font-size:.75rem;color:var(--slate);font-weight:500;">All sent alerts — activate, deactivate, or delete</div>
          </div>
          <button onclick="loadAlerts()" class="btn-refresh-sm" style="padding:10px 14px;" title="Refresh table">
            <i class="fa-solid fa-rotate-right"></i>
          </button>
        </div>
        <div class="table-scroll">
          <table class="alert-table">
            <thead>
              <tr>
                <th style="min-width:260px;">Message</th>
                <th style="min-width:140px;"><i class="fa-solid fa-location-dot me-1"></i> Target Area</th>
                <th style="min-width:100px;">Status</th>
                <th style="min-width:130px;">Expires</th>
                <th style="min-width:50px;">ID</th>
                <th style="min-width:110px;text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody id="alertsBody">
              <tr>
                <td colspan="6">
                  <div class="empty-state">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <p>Loading alerts…</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
      <div class="modal-icon" style="background:rgba(239,68,68,.1);">
        <i class="fa-solid fa-trash-can" style="color:#ef4444;"></i>
      </div>
      <h5>Delete Alert?</h5>
      <p>This alert will be permanently removed and cannot be recovered.</p>
      <div class="modal-btns">
        <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
        <button class="btn-confirm-del" id="confirmDelBtn" onclick="confirmDelete()">
          <i class="fa-solid fa-trash-can me-2"></i>Delete
        </button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function toast(msg, type = 's', dur = 3800) {
      const w = document.getElementById('toastWrap');
      const el = document.createElement('div');
      el.className = `t-item ${type}`;
      const ico = type === 's' ? 'fa-circle-check' : type === 'e' ? 'fa-circle-xmark' : 'fa-triangle-exclamation';
      el.innerHTML = `<i class="fa-solid ${ico}"></i><span>${msg}</span>`;
      w.appendChild(el);
      setTimeout(() => {
        el.classList.add('hide');
        setTimeout(() => el.remove(), 350);
      }, dur);
    }

    let selectedAreas = [];

    function addArea() {
      const sel = document.getElementById('areaSelect');
      const val = sel.value.trim();
      if (!val) return;
      if (selectedAreas.includes(val)) {
        toast('Area already added', 'w');
        return;
      }
      selectedAreas.push(val);
      renderAreaTags();
      sel.value = '';
    }

    function removeArea(val) {
      selectedAreas = selectedAreas.filter(a => a !== val);
      renderAreaTags();
    }

    function renderAreaTags() {
      const wrap = document.getElementById('areaTags');
      document.getElementById('selectedAreas').value = selectedAreas.join(',');
      if (selectedAreas.length === 0) {
        wrap.innerHTML = '';
        return;
      }
      wrap.innerHTML = selectedAreas.map(a =>
        `<span class="area-tag">
      <i class="fa-solid fa-location-dot" style="font-size:.7rem;"></i> ${escHtml(a)}
      <span class="rm" onclick="removeArea('${escHtml(a)}')" title="Remove">×</span>
    </span>`
      ).join('');
    }

    async function loadAlerts() {
      try {
        const res = await fetch('../api/set_alert.php');
        const data = await res.json();
        renderAlerts(data.alerts || []);
      } catch (e) {
        toast('Failed to load alerts', 'e');
        document.getElementById('alertsBody').innerHTML = `
      <tr><td colspan="6">
        <div class="empty-state">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <p>Failed to load. Check API connection.</p>
        </div>
      </td></tr>`;
      }
    }

    function renderAlerts(alerts) {
      const body = document.getElementById('alertsBody');
      const banner = document.getElementById('alertBanner');
      const marquee = document.getElementById('alertMarquee');
      const badge = document.getElementById('alertStatusBadge');
      const activeBox = document.getElementById('activeAlertBox');
      const noBox = document.getElementById('noAlertBox');

      const activeOnes = alerts.filter(a => a.is_active == 1);
      const hasActive = activeOnes.length > 0;

      document.getElementById('statTotal').textContent = alerts.length;
      document.getElementById('statActive').textContent = activeOnes.length;
      document.getElementById('statInactive').textContent = alerts.filter(a => a.is_active != 1).length;

      if (alerts.length === 0) {
        body.innerHTML = `<tr><td colspan="6"><div class="empty-state">
      <i class="fa-solid fa-bell-slash"></i><p>No alerts found. Broadcast your first alert!</p>
    </div></td></tr>`;

        badge.innerHTML = '<span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;"></span> No Alert';
        badge.className = 'status-pill inactive';
        activeBox.classList.add('d-none');
        activeBox.innerHTML = '';
        noBox.style.display = 'flex';
        banner.style.display = 'none';
        marquee.innerHTML = '';
        return;
      }

      if (hasActive) {
        badge.innerHTML = `<span class="pulse-dot"></span> ${activeOnes.length} Active Alert(s)`;
        badge.className = 'status-pill active';
        activeBox.classList.remove('d-none');
        noBox.style.display = 'none';
        banner.style.display = 'block';

        let marqueeText = '';
        let activeBoxHtml = '';

        activeOnes.forEach(a => {
          let rawArea = a.area || a.target_area || a.alert_area || '';
          const areaDisplay = rawArea.trim() !== '' ? rawArea : 'All Areas';

          marqueeText += `⚠ <strong>[${escHtml(areaDisplay)}]</strong> ${escHtml(a.message)} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; `;

          activeBoxHtml += `
        <div class="d-flex align-items-start pb-2 mb-2 border-bottom border-danger-subtle text-start w-100" style="gap: 10px;">
          <div style="background:#fee2e2; border-radius:8px; width:30px; height:30px; display:flex; align-items:center; justify-content:center; color:#dc2626; flex-shrink:0;">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 0.85rem;"></i>
          </div>
          <div class="flex-grow-1">
            <div class="fw-bold text-danger mb-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">
              Live Area: <span class="bg-danger text-white px-2 py-0.5 rounded-pill" style="font-size:0.68rem; font-weight:700;">${escHtml(areaDisplay)}</span>
            </div>
            <div style="font-size:.85rem;color:#1e293b;font-weight:600;line-height:1.4;">${escHtml(a.message)}</div>
          </div>
          <button onclick="toggleAlert(${a.id}, false)" title="Deactivate this alert"
            style="flex-shrink:0;background:rgba(239,68,68,.1);border:1.5px solid rgba(239,68,68,.25);border-radius:8px;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#dc2626;font-size:.78rem;">
            <i class="fa-solid fa-pause"></i>
          </button>
        </div>
      `;
        });

        if (activeOnes.length > 1) {
          activeBoxHtml += `
        <div class="d-flex justify-content-end w-100 pt-1">
          <button onclick="deactivateAll()" style="background:none; border:none; color:#dc2626; font-size:0.75rem; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:4px;">
            <i class="fa-solid fa-power-off"></i> Turn Off All Alerts
          </button>
        </div>
      `;
        }

        activeBox.innerHTML = activeBoxHtml;
        marquee.innerHTML = marqueeText;
      } else {
        badge.innerHTML = '<span style="width:7px;height:7px;border-radius:50%;background:#10b981;display:inline-block;"></span> No Alert';
        badge.className = 'status-pill inactive';
        activeBox.classList.add('d-none');
        activeBox.innerHTML = '';
        noBox.style.display = 'flex';
        banner.style.display = 'none';
        marquee.innerHTML = '';
      }

      body.innerHTML = alerts.map(a => {
        const isActive = a.is_active == 1;
        const isExpired = a.expires_at && new Date(a.expires_at) < new Date();
        let statusBadge = isActive ?
          `<span class="tbl-badge b-active"><span class="pulse-dot"></span> Active</span>` :
          isExpired ?
          `<span class="tbl-badge b-expired"><i class="fa-solid fa-hourglass-end" style="font-size:.65rem;"></i> Expired</span>` :
          `<span class="tbl-badge b-inactive"><i class="fa-solid fa-circle-pause" style="font-size:.65rem;"></i> Inactive</span>`;

        let rawArea = a.area || a.target_area || a.alert_area || '';
        const areas = rawArea ? rawArea.split(',').map(x => x.trim()).filter(Boolean) : [];
        const areaHtml = areas.length > 0 ?
          areas.map(ar => `<span class="area-chip ${ar.toLowerCase().includes('all') ? 'all-chip' : ''}"><i class="fa-solid fa-location-dot" style="font-size:.6rem; margin-right:3px;"></i>${escHtml(ar)}</span>`).join('') :
          `<span class="area-chip all-chip"><i class="fa-solid fa-globe" style="font-size:.6rem; margin-right:3px;"></i>All Areas</span>`;

        const toggleBtn = isActive ?
          `<button class="act-btn act-deactivate" onclick="toggleAlert(${a.id}, false)" title="Deactivate"><i class="fa-solid fa-pause"></i></button>` :
          `<button class="act-btn act-activate"   onclick="toggleAlert(${a.id}, true)"  title="Activate"><i class="fa-solid fa-play"></i></button>`;

        return `<tr>
      <td><div class="msg-text" title="${escHtml(a.message)}">${escHtml(a.message)}</div></td>
      <td><div style="display:flex;flex-wrap:wrap;gap:4px;">${areaHtml}</div></td>
      <td>${statusBadge}</td>
      <td><span class="date-txt">${fmtDate(a.expires_at)}</span></td>
      <td><span style="font-size:.75rem;color:var(--slate);font-weight:600;">#${a.id}</span></td>
      <td>
        <div class="actions-cell" style="justify-content:flex-end;">
          ${toggleBtn}
          <button class="act-btn act-delete" onclick="openDeleteModal(${a.id})" title="Delete permanently"><i class="fa-solid fa-trash-can"></i></button>
        </div>
      </td>
    </tr>`;
      }).join('');
    }

    async function sendAlert() {
      const msg = document.getElementById('alertMessage').value.trim();
      const exp = document.getElementById('alertExpiry').value;
      const area = document.getElementById('selectedAreas').value.trim();

      if (!msg) {
        toast('Alert message cannot be empty!', 'w');
        return;
      }

      const fd = new FormData();
      fd.append('action', 'set');
      fd.append('alert_message', msg);
      if (area) {
        fd.append('area', area);
        fd.append('target_area', area);
        fd.append('alert_area', area);
      }
      if (exp) fd.append('expires_at', exp.replace('T', ' ') + ':00');

      try {
        const r = await fetch('../api/set_alert.php', {
          method: 'POST',
          body: fd
        });
        const d = await r.json();
        if (d.status === 'success') {
          document.getElementById('alertMessage').value = '';
          document.getElementById('alertExpiry').value = '';
          selectedAreas = [];
          renderAreaTags();
          toast('Alert broadcast successfully!', 's');
          loadAlerts();
        } else toast('Error: ' + (d.message || 'Unknown'), 'e');
      } catch (e) {
        toast('Network error', 'e');
      }
    }

    async function deactivateAll() {
      const fd = new FormData();
      fd.append('action', 'deactivate');
      await fetch('../api/set_alert.php', {
        method: 'POST',
        body: fd
      });
      toast('All alerts deactivated', 's');
      loadAlerts();
    }

    async function toggleAlert(id, makeActive) {
      const fd = new FormData();
      fd.append('action', makeActive ? 'activate' : 'deactivate_one');
      fd.append('id', id);
      try {
        const r = await fetch('../api/set_alert.php', {
          method: 'POST',
          body: fd
        });
        const d = await r.json();
        if (d.status === 'success') {
          toast(makeActive ? 'Alert activated' : 'Alert deactivated', 's');
          loadAlerts();
        } else toast('Error: ' + d.message, 'e');
      } catch (e) {
        toast('Network error', 'e');
      }
    }

    let pendingDeleteId = null;

    function openDeleteModal(id) {
      pendingDeleteId = id;
      document.getElementById('deleteModal').classList.add('show');
    }

    function closeDeleteModal() {
      pendingDeleteId = null;
      document.getElementById('deleteModal').classList.remove('show');
    }

    async function confirmDelete() {
      if (!pendingDeleteId) return;
      const fd = new FormData();
      fd.append('action', 'delete');
      fd.append('id', pendingDeleteId);
      try {
        await fetch('../api/set_alert.php', {
          method: 'POST',
          body: fd
        });
        toast('Alert deleted', 's');
        closeDeleteModal();
        loadAlerts();
      } catch (e) {
        toast('Delete failed', 'e');
      }
    }

    document.getElementById('deleteModal').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteModal();
    });

    function fmtDate(s) {
      if (!s) return '—';
      const d = new Date(s);
      return d.toLocaleDateString('en-IN', {
          day: '2-digit',
          month: 'short'
        }) + ' ' +
        d.toLocaleTimeString('en-IN', {
          hour: '2-digit',
          minute: '2-digit'
        });
    }

    function escHtml(s) {
      return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    document.addEventListener('DOMContentLoaded', loadAlerts);
    setInterval(loadAlerts, 60000);
  </script>
</body>

</html>