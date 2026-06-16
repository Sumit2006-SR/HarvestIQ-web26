<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// ── YOUR API KEY ──────────────────────────────────────────
define('OWM_API_KEY', 'd204e5df4582ea631ab1870bfb91ec7b'); // ← এখানে key দাও
define('OWM_BASE',    'https://api.openweathermap.org/data/2.5/weather');


$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'harvestiq_db';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) jsonError('DB error: ' . $conn->connect_error);

$city = isset($_GET['city']) ? trim($_GET['city']) : null;
$lat  = isset($_GET['lat'])  ? (float)$_GET['lat'] : null;
$lon  = isset($_GET['lon'])  ? (float)$_GET['lon'] : null;

if ($lat !== null && $lon !== null) {
  $url = OWM_BASE . "?lat={$lat}&lon={$lon}&appid=" . OWM_API_KEY . "&units=metric";
} elseif ($city) {
  $url = OWM_BASE . "?q=" . urlencode($city) . "&appid=" . OWM_API_KEY . "&units=metric";
} else {
  jsonError('No location provided.');
}

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT        => 10,
  CURLOPT_SSL_VERIFYPEER => false,
]);
$raw  = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$cerr = curl_error($ch);
curl_close($ch);

if ($cerr)        jsonError('cURL error: ' . $cerr);
if ($http !== 200) jsonError("OWM returned HTTP {$http}. Check API key or city name.");

$w = json_decode($raw, true);
if (!$w || !isset($w['main'])) jsonError('Invalid weather data received.');

// ── EXTRACT ───────────────────────────────────────────────
$temperature    = round($w['main']['temp']);
$feels_like     = round($w['main']['feels_like']);
$humidity       = $w['main']['humidity'];
$pressure       = $w['main']['pressure'];
$wind_speed     = round(($w['wind']['speed'] ?? 0) * 3.6, 1); // m/s→km/h
$wind_deg       = $w['wind']['deg'] ?? 0;
$visibility     = isset($w['visibility']) ? round($w['visibility'] / 1000, 1) : 'N/A';
$main_condition = $w['weather'][0]['main'];
$description    = ucfirst($w['weather'][0]['description']);
$clouds         = $w['clouds']['all'] ?? 0;
$rain_1h        = $w['rain']['1h'] ?? 0;
$location_name  = $w['name'] . ', ' . ($w['sys']['country'] ?? '');
$res_lat        = $w['coord']['lat'];
$res_lon        = $w['coord']['lon'];
$tz             = $w['timezone'] ?? 0;
$sunrise        = gmdate('h:i A', ($w['sys']['sunrise'] ?? 0) + $tz);
$sunset         = gmdate('h:i A', ($w['sys']['sunset']  ?? 0) + $tz);

// ── SUITABILITY SCORE ─────────────────────────────────────
$score = 100;
if ($temperature < 10 || $temperature > 42) $score -= 35;
elseif ($temperature < 15 || $temperature > 38) $score -= 15;
if ($humidity < 20 || $humidity > 90) $score -= 20;
elseif ($humidity < 30 || $humidity > 80) $score -= 8;
if ($wind_speed > 50) $score -= 25;
elseif ($wind_speed > 30) $score -= 10;
if ($rain_1h > 0 && $rain_1h <= 10) $score += 5;
if ($rain_1h > 20) $score -= 15;
if (in_array($main_condition, ['Thunderstorm', 'Tornado', 'Squall'])) $score -= 40;
$score = max(5, min(100, $score));

// ── RISK ──────────────────────────────────────────────────
if ($score >= 75) {
  $risk = 'Low';
  $action = 'success';
} elseif ($score >= 45) {
  $risk = 'Moderate';
  $action = 'warning';
} else {
  $risk = 'High';
  $action = 'danger';
}

// ── ADVISORY ──────────────────────────────────────────────
$advisory = generateAdvisory($main_condition, $temperature, $humidity, $wind_speed, $rain_1h, $score);

// ── ADMIN ALERT FROM DB (UPDATED FOR TARGET AREA) ─────────
$admin_alert = '';
// Auto-create table if missing
$conn->query("CREATE TABLE IF NOT EXISTS weather_alerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT NOT NULL,
  target_area VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  expires_at DATETIME DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Fetch message and target_area
$res = $conn->query("SELECT message, target_area FROM weather_alerts
  WHERE is_active=1 AND (expires_at IS NULL OR expires_at > NOW())
  ORDER BY created_at DESC LIMIT 1");

if ($res && $row = $res->fetch_assoc()) {
  $area = !empty($row['target_area']) ? $row['target_area'] : 'All Areas';
  // Format the alert to show area name first
  $admin_alert = "🚨 [" . $area . "] " . $row['message'];
}

// ── LOG TO DB ─────────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS weather_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  location VARCHAR(255), lat DOUBLE, lon DOUBLE,
  temperature INT, humidity INT, wind_speed DECIMAL(5,1),
  pressure INT, weather_condition VARCHAR(100),
  suitability_score INT, risk_level VARCHAR(20),
  fetched_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$stmt = $conn->prepare("INSERT INTO weather_logs
  (location,lat,lon,temperature,humidity,wind_speed,pressure,weather_condition,suitability_score,risk_level)
  VALUES (?,?,?,?,?,?,?,?,?,?)");
if ($stmt) {
  $stmt->bind_param(
    'sddiiidsis',
    $location_name,
    $res_lat,
    $res_lon,
    $temperature,
    $humidity,
    $wind_speed,
    $pressure,
    $description,
    $score,
    $risk
  );
  $stmt->execute();
  $stmt->close();
}
$conn->close();

// ── RESPOND ───────────────────────────────────────────────
echo json_encode([
  'status'            => 'success',
  'location'          => $location_name,
  'lat'               => $res_lat,
  'lon'               => $res_lon,
  'temperature'       => $temperature,
  'feels_like'        => $feels_like,
  'humidity'          => $humidity,
  'pressure'          => $pressure,
  'wind_speed'        => $wind_speed,
  'wind_direction'    => windDir($wind_deg),
  'visibility'        => $visibility,
  'weather_condition' => $description,
  'main_condition'    => $main_condition,
  'clouds'            => $clouds,
  'rain_1h'           => $rain_1h,
  'sunrise'           => $sunrise,
  'sunset'            => $sunset,
  'suitability_score' => $score,
  'risk_level'        => $risk,
  'action_type'       => $action,
  'crop_advisory'     => $advisory,
  'admin_alert'       => $admin_alert,
]);

// ── HELPERS ───────────────────────────────────────────────
function generateAdvisory($cond, $temp, $hum, $wind, $rain, $score)
{
  $c = strtolower($cond);
  if (str_contains($c, 'thunderstorm'))
    return "⚡ SEVERE STORM ALERT: Move all farm equipment to shelter immediately. Avoid open fields. Delay pesticide/fertiliser application. Check drainage channels to prevent waterlogging.";
  if (str_contains($c, 'tornado') || str_contains($c, 'squall'))
    return "🌪️ EXTREME WEATHER: All outdoor agricultural operations must be suspended. Secure poly-houses and shade nets. Take shelter immediately.";
  if ($rain > 15)
    return "🌧️ HEAVY RAINFALL: Postpone sowing, spraying, and harvesting. Inspect field drainage. After rain subsides, check for soil erosion and apply mulch. Rice paddies may benefit — monitor water levels.";
  if (str_contains($c, 'rain') || str_contains($c, 'drizzle')) {
    if ($temp >= 22 && $temp <= 32)
      return "🌦️ LIGHT RAIN — FAVOURABLE: Ideal for transplanting seedlings and applying organic compost. Skip irrigation today. Avoid fungicide spraying; wait 4–6 hours post-rain.";
    return "🌧️ RAINY CONDITIONS: Pause chemical spraying. Monitor humidity-sensitive crops (tomatoes, grapes) for early signs of fungal infection.";
  }
  if (str_contains($c, 'clear') || str_contains($c, 'sun')) {
    if ($temp > 38)
      return "☀️ EXTREME HEAT: Water crops before 8 AM or after 6 PM. Apply mulch around plant bases. Consider temporary shade nets for vegetables. Avoid field labour 11 AM–4 PM.";
    if ($temp >= 25 && $temp <= 35 && $hum >= 40 && $hum <= 70)
      return "☀️ EXCELLENT CONDITIONS: Perfect for foliar spray, manual weeding, and field scouting. Ideal for harvesting mature Rabi crops. Plan fertiliser top-dressing in the morning.";
    return "🌤️ CLEAR SKIES: Good for general farm work. Monitor soil moisture — irrigation may be needed if dry spell continues.";
  }
  if (str_contains($c, 'cloud'))
    return "☁️ OVERCAST: Good for transplanting — reduced transplant shock. Suitable for pesticide application. Monitor for pest activity as cloudy days favour some insects.";
  if (str_contains($c, 'fog') || str_contains($c, 'mist'))
    return "🌫️ FOG/MIST: Delay spraying until fog lifts (typically by 10 AM). High moisture increases risk of late blight in potatoes and blast disease in paddy.";
  if ($wind > 40)
    return "💨 HIGH WINDS: Secure irrigation pipes, shade nets, poly-house covers. Avoid pesticide spraying (drift risk). Stake tall crops like maize and sunflower.";
  if ($temp < 10)
    return "❄️ COLD STRESS: Protect sensitive crops with frost covers or straw mulch. Delay germination-stage sowing. Ensure greenhouse heating for nurseries.";
  if ($hum > 85)
    return "💧 HIGH HUMIDITY: Elevated risk of fungal diseases. Scout crops for early symptoms and apply preventive fungicide. Ensure good inter-row spacing for air circulation.";
  if ($score >= 80)
    return "✅ OPTIMAL CONDITIONS: All agro-meteorological parameters are within ideal range. Proceed with planned field operations — sowing, irrigation, fertilisation, or harvesting as per crop calendar.";
  return "📊 MODERATE CONDITIONS: Exercise caution with sensitive operations. Check crop-specific thresholds before applying agrochemicals. Maintain regular monitoring.";
}

function windDir($deg)
{
  $d = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
  return $d[round($deg / 22.5) % 16];
}

function jsonError($msg)
{
  echo json_encode(['status' => 'error', 'message' => $msg]);
  exit;
}