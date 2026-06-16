<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'harvestiq_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  echo json_encode(['status' => 'error', 'message' => 'DB Connection failed']);
  exit;
}

// Ensure table exists with target_area column
$conn->query("CREATE TABLE IF NOT EXISTS weather_alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT NOT NULL,
  target_area VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  expires_at DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$action = $_POST['action'] ?? ($_GET['action'] ?? 'get');

if ($action === 'set') {
  $msg = $conn->real_escape_string($_POST['alert_message'] ?? '');
  $area = $conn->real_escape_string($_POST['target_area'] ?? '');
  $exp = !empty($_POST['expires_at']) ? $conn->real_escape_string($_POST['expires_at']) : NULL;

  if (empty($msg)) {
    echo json_encode(['status' => 'error', 'message' => 'Message is required']);
    exit;
  }

  if ($exp) {
    $sql = "INSERT INTO weather_alerts (message, target_area, is_active, expires_at) VALUES ('$msg', '$area', 1, '$exp')";
  } else {
    $sql = "INSERT INTO weather_alerts (message, target_area, is_active, expires_at) VALUES ('$msg', '$area', 1, NULL)";
  }

  if ($conn->query($sql)) {
    echo json_encode(['status' => 'success']);
  } else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
  }
} elseif ($action === 'deactivate') {
  $conn->query("UPDATE weather_alerts SET is_active = 0");
  echo json_encode(['status' => 'success']);
} elseif ($action === 'activate') {
  $id = (int)($_POST['id'] ?? 0);
  $conn->query("UPDATE weather_alerts SET is_active = 1 WHERE id = $id");
  echo json_encode(['status' => 'success']);
} elseif ($action === 'deactivate_one') {
  $id = (int)($_POST['id'] ?? 0);
  $conn->query("UPDATE weather_alerts SET is_active = 0 WHERE id = $id");
  echo json_encode(['status' => 'success']);
} elseif ($action === 'delete') {
  $id = (int)($_POST['id'] ?? 0);
  $conn->query("DELETE FROM weather_alerts WHERE id = $id");
  echo json_encode(['status' => 'success']);
} else {
  // GET all alerts for admin history table
  $res = $conn->query("SELECT * FROM weather_alerts ORDER BY id DESC");
  $alerts = [];
  while ($row = $res->fetch_assoc()) {
    $alerts[] = $row;
  }
  echo json_encode(['status' => 'success', 'alerts' => $alerts]);
}
$conn->close();