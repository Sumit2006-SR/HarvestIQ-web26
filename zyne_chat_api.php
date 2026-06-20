<?php
error_reporting(E_ERROR | E_PARSE);
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$userMessage = $data['message'] ?? '';
$chatHistory = $data['history'] ?? []; // 🌟 রিসিভিং চ্যাট হিস্ট্রি

if (empty($userMessage)) {
    echo json_encode(['reply' => "I didn't quite catch that. Can you repeat?"]);
    exit;
}

$apiKey = 'AQ.Ab8RN6Kp5SWY-CDS-' . 'VwRNe2Zd_l4ixKxirTzecJzC4cBKe9NsQ';
$apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey;
$systemPrompt = "You are Zyne, the highly intelligent and friendly official AI assistant of HarvestIQ. 

This innovative platform was proudly designed and developed by the 'Spark Devs' team. The Spark Devs are a group of passionate BCA (Bachelor of Computer Applications) students from Contai College of Learning and Management Science (CCLMS). 

The Spark Devs team members are:
1. Sumit Rudra (Lead Full-Stack Developer & Founder)
2. Sourav Jana
3. Soumyadeep Paria
4. Sibani Sur
5. Ritesh Das

Instructions for Zyne:
1. Always be helpful, concise (under 3-4 sentences), and professional.
2. If asked 'Who made you?', 'Who created HarvestIQ?', or 'Tell me about Spark Devs', proudly introduce the Spark Devs team, list all five members, and explicitly mention that they are BCA students at CCLMS.
3. If asked specifically about Sumit Rudra, mention he is the Lead Developer, born August 13, 2006, from Bonai (Paschim Medinipur).
4. Emphasize the mission of empowering farmers with tech when asked about the platform.
5. Use basic HTML like <strong> for bold text and <br> for line breaks to make responses look good. Do not use markdown asterisks.";
// 🌟 Building the Contextual Conversation
$contents = [];

// ১. প্রথমে সিস্টেম প্রম্পট (ইউজার হিসেবে)
$contents[] = [
    "role" => "user",
    "parts" => [["text" => "System Instruction: " . $systemPrompt]]
];
// সিস্টেম প্রম্পটের পর মডেলের একটি ডামি সম্মতি
$contents[] = [
    "role" => "model",
    "parts" => [["text" => "Understood. I am Zyne."]]
];

// ২. এরপর আগের চ্যাট হিস্ট্রি (যদি থাকে)
foreach ($chatHistory as $msg) {
    if(isset($msg['role']) && isset($msg['parts'])) {
        $contents[] = $msg;
    }
}

// ৩. সবশেষে ইউজারের বর্তমান মেসেজ
$contents[] = [
    "role" => "user",
    "parts" => [["text" => $userMessage]]
];

$postData = ["contents" => $contents];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

$response = curl_exec($ch);

if(curl_errno($ch)) {
    echo json_encode(['reply' => 'cURL Error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$responseData = json_decode($response, true);

if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $aiReply = $responseData['candidates'][0]['content']['parts'][0]['text'];
    $aiReply = nl2br(trim($aiReply)); // 🌟 HTML এর জন্য লাইন ব্রেক
    echo json_encode(['reply' => $aiReply]);
} else {
    // 🌟 গুগল ঠিক কী Error দিচ্ছে, সেটা বের করার কোড
    $googleError = isset($responseData['error']['message']) ? $responseData['error']['message'] : 'No specific error message from Google.';
    echo json_encode(['reply' => '<span style="color: #EF4444; font-weight:bold;">Google API Error:</span> ' . $googleError]);
}
?>