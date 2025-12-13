<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db.php';
// Check if user is logged in
if (!isset($_SESSION['user'])) {
    die("You must be logged in to summarize and save.");
}

$userEmail = $_SESSION['user']['email'];

require __DIR__ . '/vendor/autoload.php';

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

$apiKey = "TNoquMPNxTn84DOxOuKshaBvaoX4wrByhZhihprW";
$text = "";

// ================= FILE UPLOAD ================= //
if (isset($_FILES['bookFile']) && $_FILES['bookFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['bookFile']['tmp_name'];
    $fileName = $_FILES['bookFile']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    try {
        if ($fileExt === 'txt') {
            $text = file_get_contents($fileTmpPath);
        } elseif ($fileExt === 'pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($fileTmpPath);
            $text = $pdf->getText();
        } elseif ($fileExt === 'doc' || $fileExt === 'docx') {
            $phpWord = IOFactory::load($fileTmpPath);
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }
        }
    } catch (Exception $e) {
        die("❌ Error reading file: " . $e->getMessage());
    }
}

// ================= TEXT INPUT ================= //
if (!empty($_POST['bookText'])) {
    $text = $_POST['bookText'];
}

$text = trim($text);
if (empty($text)) {
    die("❌ No valid text found in input or uploaded file.");
}

// Limit input size
if (strlen($text) > 20000) {
    $text = substr($text, 0, 20000);
}

// ================= COHERE CHAT API ================= //
$url = "https://api.cohere.com/v2/chat";

$data = [
    "model" => "command-a-03-2025",
    "messages" => [
        [
            "role" => "system",
            "content" => "You are an expert summarizer. Always respond ONLY in JSON, no extra text. Example:\n\n{
              \"main_topic\": \"(1 line topic)\",
              \"important_points\": [\"point 1\", \"point 2\", \"point 3\"],
              \"final_summary\": \"(short and clear summary)\"
            }"
        ],
        ["role" => "user", "content" => $text]
    ],
    "max_tokens" => 800
];

$headers = [
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("❌ cURL Error: " . curl_error($ch));
}
curl_close($ch);

$result = json_decode($response, true);

// ================= PARSE RESPONSE ================= //
$mainTopic = "⚠️ Not generated.";
$points = [];
$final = "⚠️ Not generated.";
$summaryText = ""; // full summary text to store

if (isset($result['message']['content'][0]['text'])) {
    $raw = $result['message']['content'][0]['text'];

    // --- Try direct JSON decode ---
    $parsed = json_decode($raw, true);

    // --- If fail, extract JSON substring ---
    if (!$parsed) {
        if (preg_match('/\{.*\}/s', $raw, $m)) {
            $parsed = json_decode($m[0], true);
        }
    }

    // --- If still fail, fallback to regex parsing ---
    if (!$parsed) {
        if (preg_match('/Main Topic[:\-]?(.*)/i', $raw, $m)) {
            $mainTopic = trim($m[1]);
        }
        if (preg_match('/Important Points[:\-]?(.*)(Final Summary|$)/is', $raw, $m)) {
            $points = explode("\n", trim($m[1]));
        }
        if (preg_match('/Final Summary[:\-]?(.*)/is', $raw, $m)) {
            $final = trim($m[1]);
        }

        // Compose summary text manually
        $summaryText = "Main Topic: $mainTopic\nImportant Points:\n";
        foreach ($points as $p) {
            $summaryText .= "- " . trim($p) . "\n";
        }
        $summaryText .= "Final Summary:\n$final";
    } else {
        // ✅ JSON parsed successfully
        $mainTopic = $parsed['main_topic'] ?? $mainTopic;
        $points = $parsed['important_points'] ?? [];
        $final = $parsed['final_summary'] ?? $final;

        // Compose summary text string for DB
        $summaryText = "Main Topic: $mainTopic\nImportant Points:\n";
        foreach ($points as $p) {
            $summaryText .= "- " . trim($p) . "\n";
        }
        $summaryText .= "Final Summary:\n$final";
    }
} else {
    $summaryText = "⚠️ Summary not generated.";
}

// ================= SAVE TO DATABASE ================= //
$servername = "sql105.infinityfree.com";
$username   = "if0_39907292";
$password   = "6QecRDL6dargJVf";
$database   = "if0_39907292_user";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Set timezone explicitly (important!)
date_default_timezone_set("Asia/Kolkata");
$createdAt = date("Y-m-d H:i:s");

$stmt = $con->prepare("INSERT INTO summaries (user_email, original_text, summary, created_at) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $userEmail, $text, $summaryText, $createdAt);

if (!$stmt->execute()) {
    die("Failed to save summary: " . $stmt->error);
}

$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Summarizer Result</title>
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        margin:0;
        padding:0;
        color:#f1f1f1;
    }
    header {
        background: linear-gradient(90deg, #ff4fa1, #7a5fff);
        color:white;
        padding:20px 25px;
        font-size:24px;
        font-weight:bold;
        text-shadow:0px 0px 6px rgba(255,255,255,0.6);
    }
    .container {
        max-width:1100px;
        margin:25px auto;
        padding:20px;
        text-align:left;
    }
    .card {
        background: rgba(20,20,30,0.9);
        padding:20px;
        margin-bottom:25px;
        border-radius:10px;
        border:1px solid rgba(255,255,255,0.15);
        box-shadow: 0px 0px 15px rgba(155,89,182,0.6);
    }
    h2 {
        font-size:18px;
        margin-top:0;
        color:#ff9ecf;
        border-left:4px solid #9fa8ff;
        padding-left:10px;
    }
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        font-size:14px;
        line-height:1.6;
        color:#ddd;
    }
    .summary {
        font-size:15px;
        line-height:1.7;
        color:#f5f5f5;
        margin-bottom:15px;
    }
    .btn {
        display:inline-block;
        background: linear-gradient(90deg, #ff4fa1, #7a5fff);
        color:white;
        padding:12px 20px;
        border-radius:6px;
        text-decoration:none;
        font-weight:bold;
        transition:0.3s;
        box-shadow:0px 0px 8px rgba(255,158,207,0.6);
    }
    .btn:hover {
        opacity:0.9;
        box-shadow:0px 0px 12px rgba(159,168,255,0.8);
    }
    ul { margin:0; padding-left:20px; }
</style>
</head>
<body>

<header>⚡ Smart Summarizer</header>
<div class="container">
    <div class="card">
        <h2>✅ Original Text (Preview)</h2>
        <pre><?= htmlspecialchars(substr($text, 0, 1500)) ?>...</pre>
    </div>

    <div class="card">
        <h2>📌 Main Topic</h2>
        <div class="summary"><?= htmlspecialchars($mainTopic) ?></div>

        <h2>📑 Important Points</h2>
        <div class="summary">
            <?php if (!empty($points)): ?>
                <ul>
                    <?php foreach ($points as $p): ?>
                        <?php if (trim($p)): ?>
                            <li><?= htmlspecialchars($p) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                ⚠️ Not generated.
            <?php endif; ?>
        </div>

        <h2>📝 Final Summary</h2>
        <div class="summary"><?= htmlspecialchars($final) ?></div>
    </div>

    <a href="search.html" class="btn">🔄 Summarize Another</a>
    <a href="my_summaries.php" class="btn">📚 My Summaries</a>
</div>
</body>
</html>
