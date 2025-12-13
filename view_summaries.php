<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user'])) {
    die("You must be logged in to view this page.");
}

$servername = "sql105.infinityfree.com";
$username = "if0_39907292";
$password = "6QecRDL6dargJVf";
$database = "if0_39907292_user";

$con = new mysqli($servername, $username, $password, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$id = intval($_GET['id'] ?? 0);
$user_email = $_SESSION['user']['email'];

$stmt = $con->prepare("SELECT summary FROM summaries WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $id, $user_email);
$stmt->execute();
$stmt->bind_result($summary);
$stmt->fetch();
$stmt->close();
$con->close();

if (!$summary) die("Summary not found or permission denied.");

$mainTopic = '';
$points = [];
$finalSummary = '';

if (preg_match('/Main Topic:\s*(.*?)\nImportant Points:/is', $summary, $mt))
    $mainTopic = trim($mt[1]);
if (preg_match('/Important Points:\n(.*?)\nFinal Summary:/is', $summary, $p)) {
    $pointsRaw = $p[1];
    foreach (explode("\n", $pointsRaw) as $line) {
        $line = trim($line);
        if ($line && $line[0] == '-') $line = trim(substr($line, 1));
        if ($line !== '') $points[] = $line;
    }
}
if (preg_match('/Final Summary:\n(.*)/is', $summary, $fsum))
    $finalSummary = trim($fsum[1]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>View Summary</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<style>
body {
  margin: 0;
  background: radial-gradient(ellipse at top left, #232042 40%, #181825 100%);
  min-height: 100vh;
  font-family: 'Segoe UI', Arial, sans-serif;
  color: #f1f1f1;
}
.outer-card {
  background: rgba(22,17,35,0.97);
  margin: 30px auto;
  max-width: 1100px;
  border-radius: 18px;
  box-shadow: 0 0 38px #7a5fff55, 0 0 4px #ff9ecfaa inset;
  padding: 40px 46px 36px;
  border: 2px solid #2b264b;
}
.section-block {
  margin-bottom: 35px;
}
.section-title {
  font-size: 1.39rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 12px;
  color: #ff9ecf;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
  background: linear-gradient(90deg, #ff9ecf 70%, #9fa8ff 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  user-select: none;
}
.section-title i {
  color: #ff4fa1;
  font-size: 1.27em;
  filter: drop-shadow(0 0 7px #ff4fa166);
}
ul.point-list {
  margin: 0;
  margin-top: 16px;
  margin-bottom: 8px;
  padding-left: 23px;
  color: #f8f8ff;
}
ul.point-list li {
  margin-bottom: 11px;
  font-size: 1.10em;
  line-height: 1.65;
}
.final-summary-block {
  background: linear-gradient(100deg, #24243e 75%, #181825 100%);
  border-radius: 13px;
  padding: 17px 16px 16px;
  box-shadow: 0 0 14px #ff9ecf44;
  margin-top: 9px;
  margin-bottom: 10px;
  color: #ffeaf8;
  font-size: 1.11em;
}
@media (max-width: 700px) {
  .outer-card {
    padding: 20px 7px 19px;
  }
  .section-title {
    font-size: 1.14rem;
  }
}
.btn-group {
  margin-top: 35px;
  text-align: left;
}
.btn {
  background: linear-gradient(90deg,#ff9ecf 80%,#7a5fff 100%);
  color: #181825;
  font-weight: 700;
  padding: 11px 32px;
  border-radius: 10px;
  text-decoration: none;
  font-size: 15px;
  box-shadow: 0 0 12px #ff9ecf33;
  display: inline-block;
  margin-right: 18px;
  margin-top: 8px;
  transition: background 0.3s;
}
.btn:hover {
  background: linear-gradient(90deg,#7a5fff 80%,#ff9ecf 100%);
}
</style>
</head>
<body>
<div class="outer-card">

  <div class="section-block">
    <div class="section-title"><i class="fa-solid fa-thumbtack"></i> Main Topic</div>
    <div><?= htmlspecialchars($mainTopic) ?></div>
  </div>

  <div class="section-block">
    <div class="section-title"><i class="fa-solid fa-file-alt"></i> Important Points</div>
    <ul class="point-list">
      <?php foreach ($points as $p): ?>
        <li><?= htmlspecialchars($p) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="section-block">
    <div class="section-title"><i class="fa-solid fa-flag-checkered"></i> Final Summary</div>
    <div class="final-summary-block"><?= htmlspecialchars($finalSummary) ?></div>
  </div>

  <div class="btn-group">
    <a class="btn" href="dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <a class="btn" href="my_summaries.php"><i class="fas fa-file-alt"></i> My Summaries</a>
  </div>
</div>
</body>
</html>
