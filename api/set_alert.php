<?php
require '../db.php';

// Verify admin access
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit();
}

$admin_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Handle different alert actions
switch($action) {
    case 'set':
        handleSetAlert($conn, $admin_id);
        break;
    case 'get':
        handleGetAlerts($conn);
        break;
    case 'activate':
        handleActivateAlert($conn, $admin_id);
        break;
    case 'deactivate':
        handleDeactivateAll($conn, $admin_id);
        break;
    case 'deactivate_one':
        handleDeactivateOne($conn, $admin_id);
        break;
    case 'delete':
        handleDeleteAlert($conn, $admin_id);
        break;
    case 'stats':
        handleGetStats($conn);
        break;
    default:
        handleGetAlerts($conn);
}

function handleSetAlert($conn, $admin_id) {
    $message = trim($_POST['alert_message'] ?? '');
    $target_area = trim($_POST['area'] ?? '');
    $expires_at = $_POST['expires_at'] ?? null;
    $severity = trim($_POST['severity'] ?? 'high');
    $alert_type = trim($_POST['alert_type'] ?? 'weather');

    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Alert message is required']);
        return;
    }

    if (empty($target_area)) {
        $target_area = 'All West Bengal';
    }

    // If no expiry set, default to 24 hours from now
    if (empty($expires_at)) {
        $expires_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
    }

    $stmt = $conn->prepare("
        INSERT INTO weather_alerts 
        (admin_id, message, target_area, severity, alert_type, is_active, expires_at, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, 1, ?, NOW(), NOW())
    ");

    $is_active = 1;
    $stmt->bind_param('issssss', $admin_id, $message, $target_area, $severity, $alert_type, $expires_at);

    if ($stmt->execute()) {
        $alert_id = $stmt->insert_id;
        $stmt->close();

        // Log the action
        logAdminAction($conn, $admin_id, 'create_alert', "Created alert ID: $alert_id");

        // Notify affected users via database
        notifyAffectedFarmers($conn, $alert_id, $target_area, $message);

        echo json_encode([
            'status' => 'success',
            'message' => 'Alert broadcast successfully to farmers',
            'alert_id' => $alert_id
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create alert: ' . $stmt->error]);
    }
}

function handleGetAlerts($conn) {
    $stmt = $conn->prepare("
        SELECT id, message, target_area, severity, alert_type, is_active, 
               expires_at, created_at, admin_id,
               CASE 
                   WHEN is_active = 1 AND expires_at > NOW() THEN 'active'
                   WHEN expires_at <= NOW() THEN 'expired'
                   ELSE 'inactive'
               END as status
        FROM weather_alerts
        ORDER BY created_at DESC
        LIMIT 100
    ");

    $stmt->execute();
    $result = $stmt->get_result();
    $alerts = [];

    while ($row = $result->fetch_assoc()) {
        $alerts[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'alerts' => $alerts,
        'count' => count($alerts)
    ]);

    $stmt->close();
}

function handleActivateAlert($conn, $admin_id) {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid alert ID']);
        return;
    }

    $stmt = $conn->prepare("
        UPDATE weather_alerts 
        SET is_active = 1, updated_at = NOW() 
        WHERE id = ?
    ");

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        logAdminAction($conn, $admin_id, 'activate_alert', "Activated alert ID: $id");
        echo json_encode(['status' => 'success', 'message' => 'Alert activated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to activate alert']);
    }

    $stmt->close();
}

function handleDeactivateAll($conn, $admin_id) {
    $stmt = $conn->prepare("
        UPDATE weather_alerts 
        SET is_active = 0, updated_at = NOW() 
        WHERE is_active = 1 AND expires_at > NOW()
    ");

    if ($stmt->execute()) {
        $affected = $conn->affected_rows;
        logAdminAction($conn, $admin_id, 'deactivate_all', "Deactivated $affected alerts");
        echo json_encode(['status' => 'success', 'message' => "Deactivated $affected alert(s)"]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to deactivate alerts']);
    }

    $stmt->close();
}

function handleDeactivateOne($conn, $admin_id) {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid alert ID']);
        return;
    }

    $stmt = $conn->prepare("
        UPDATE weather_alerts 
        SET is_active = 0, updated_at = NOW() 
        WHERE id = ?
    ");

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        logAdminAction($conn, $admin_id, 'deactivate_alert', "Deactivated alert ID: $id");
        echo json_encode(['status' => 'success', 'message' => 'Alert deactivated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to deactivate alert']);
    }

    $stmt->close();
}

function handleDeleteAlert($conn, $admin_id) {
    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid alert ID']);
        return;
    }

    $stmt = $conn->prepare("
        DELETE FROM weather_alerts 
        WHERE id = ?
    ");

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        logAdminAction($conn, $admin_id, 'delete_alert', "Deleted alert ID: $id");
        echo json_encode(['status' => 'success', 'message' => 'Alert deleted permanently']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete alert']);
    }

    $stmt->close();
}

function handleGetStats($conn) {
    $stats = [];

    // Total alerts
    $result = $conn->query("SELECT COUNT(*) as total FROM weather_alerts");
    $stats['total'] = $result->fetch_assoc()['total'];

    // Active alerts
    $result = $conn->query("SELECT COUNT(*) as active FROM weather_alerts WHERE is_active = 1 AND expires_at > NOW()");
    $stats['active'] = $result->fetch_assoc()['active'];

    // Inactive alerts
    $result = $conn->query("SELECT COUNT(*) as inactive FROM weather_alerts WHERE is_active = 0 OR expires_at <= NOW()");
    $stats['inactive'] = $result->fetch_assoc()['inactive'];

    // Alerts sent today
    $result = $conn->query("SELECT COUNT(*) as today FROM weather_alerts WHERE DATE(created_at) = CURDATE()");
    $stats['today'] = $result->fetch_assoc()['today'];

    echo json_encode(['status' => 'success', 'stats' => $stats]);
}

function logAdminAction($conn, $admin_id, $action, $details) {
    $stmt = $conn->prepare("
        INSERT INTO admin_logs (admin_id, action, details, ip_address, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt->bind_param('isss', $admin_id, $action, $details, $ip);
    $stmt->execute();
    $stmt->close();
}

function notifyAffectedFarmers($conn, $alert_id, $target_area, $message) {
    // Get all farmers in target area
    $areas = explode(',', $target_area);
    $areas = array_map('trim', $areas);

    foreach ($areas as $area) {
        if (strtolower($area) === 'all west bengal' || strtolower($area) === 'all india') {
            // Notify all farmers
            $stmt = $conn->prepare("
                INSERT INTO notifications (user_id, title, message, type, reference_id, created_at) 
                SELECT id, ?, ?, 'alert', ?, NOW() FROM users WHERE role = 'farmer'
            ");

            $type = 'weather_alert';
            $title = 'Weather Alert';
            $stmt->bind_param('ssi', $title, $message, $alert_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Notify farmers in specific area
            $stmt = $conn->prepare("
                INSERT INTO notifications (user_id, title, message, type, reference_id, created_at) 
                SELECT u.id, ?, ?, 'alert', ?, NOW() 
                FROM users u 
                WHERE u.role = 'farmer' AND (u.location LIKE ? OR u.location LIKE ?)
            ");

            $type = 'weather_alert';
            $title = 'Weather Alert for Your Area';
            $area_search = '%' . $area . '%';
            $stmt->bind_param('ssisss', $title, $message, $alert_id, $area_search, $area_search);
            $stmt->execute();
            $stmt->close();
        }
    }
}

$conn->close();
