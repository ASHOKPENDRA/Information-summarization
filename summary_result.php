<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
    exit();
}

$servername = "sql105.infinityfree.com";
$username = "if0_39907292";
$password = "6QecRDL6dargJVf";
$database = "if0_39907292_user";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_GET['id'])) {
    header("Location: my_summaries.php");
    exit();
}

$id = intval($_GET['id']);
$user_email = $_SESSION['user']['email'];

$stmt = $con->prepare("SELECT summary FROM summaries WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $id, $user_email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$con->close();

if ($result->num_rows == 0) {
    echo "<p style='text-align:center;margin-top:50px;color:red;'>Summary not found.</p>";
    exit();
}

$row = $result->fetch_assoc();
$summary_text = $row['summary'];

// Split summary into lines
$lines = explode("\n", $summary_text);

// Extract main topic (first non-empty line)
$main_topic = "Untitled";
foreach ($lines as $line) {
    if (trim($line) !== '') {
        $main_topic = trim($line);
        break;
    }
}

// Find important points (lines starting with dash or bullet)
$important_points = [];
foreach ($lines as $line) {
    $line = trim($line);
    if ($line !== '' && preg_match('/^[-•*]\s*(.+)/', $line, $matches)) {
        $important_points[] = $matches[1];
    }
}

// Final summary: remaining lines not in important points
$final_summary_lines = array_filter($lines, function($line) use ($important_points) {
    $line = trim($line);
    if ($line === '') return false;
    foreach ($important_points as $pt) {
        if (strpos($line, $pt) !== false) return false;
    }
    return true;
});
$final_summary = implode(' ', $final_summary_lines);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Summary</title>
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 40px;
    background: #1b1b2f;
    color: #fff;
}

.container {
    max-width: 900px;
    margin: 0 auto;
}

.card {
    background: #1f1f35;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 0 25px rgba(0,0,0,0.5);
    margin-bottom: 30px;
}

h2 {
    color: #ff9ecf;
    margin-bottom: 12px;
}

h3 {
    color: #ff9ecf;
    margin-top: 20px;
    margin-bottom: 10px;
}

ul {
    padding-left: 20px;
}

li {
    margin-bottom: 8px;
}

.button-group {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.btn {
    padding: 12px 25px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: bold;
    transition: 0.3s;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(90deg, #ff4fa1, #7a5fff);
    color: #121212;
}

.btn-primary:hover {
    opacity: 0.9;
    box-shadow: 0 0 12px rgba(159,168,255,0.6);
}
</style>
</head>
<body>
<div class="container">
    <div class="card">
        <h3> Main Topic</h3>
        <p><?= htmlspecialchars($main_topic) ?></p>

        <?php if (!empty($important_points)): ?>
        <h3> Important Points</h3>
        <ul>
            <?php foreach ($important_points as $point): ?>
                <li><?= htmlspecialchars($point) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if (!empty(trim($final_summary))): ?>
        <h3> Final Summary</h3>
        <p><?= htmlspecialchars($final_summary) ?></p>
        <?php endif; ?>
    </div>

    <div class="button-group">
        <a href="search.php" class="btn btn-primary">Summarize Another</a>
        <a href="my_summaries.php" class="btn btn-primary">My Summaries</a>
    </div>
</div>
</body>
</html>
