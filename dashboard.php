<?php
// Prevent caching of protected dashboard page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

session_start();
require_once 'db.php';
// 10 minutes timeout
$timeout_duration = 600;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: loginpage.php?timeout=1");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time();

// check login
if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
    exit();
}

// DB connection (your credentials)
$servername = "sql105.infinityfree.com";
$username = "if0_39907292";
$passwordDb = "6QecRDL6dargJVf";
$database = "if0_39907292_user";

$con = new mysqli($servername, $username, $passwordDb, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// fetch latest summary for this user (if any)
$latestSummary = null;
$stmt = $con->prepare("SELECT id, summary FROM summaries WHERE user_email = ? ORDER BY created_at DESC LIMIT 1");
$user_email = $_SESSION['user']['email'];
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows > 0) {
    $latestSummary = $result->fetch_assoc();
}
$stmt->close();
$con->close();

function truncate_text($text, $max_length = 200) {
    return strlen($text) > $max_length ? substr($text, 0, $max_length) . '...' : $text;
}
$profile_image = $_SESSION['user']['profile_image'] ?? 'https://i.pravatar.cc/80';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard - E-Book Summarizer</title>
<style>
/* your existing CSS (kept from your theme) */
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #121212;
    color: #fff;
}
.sidebar {
    width: 220px;
    background-color: #1e1e1e;
    height: 100vh;
    position: fixed;
    padding-top: 30px;
    display: flex; flex-direction: column; align-items: center; overflow: hidden;
}
.dashboard-title { color: #9fa8ff; font-weight:bold; font-size:1.5rem; margin-bottom:20px; text-align:center; }
.profile-pic-wrapper { display:flex; flex-direction:column; align-items:center; margin-bottom:18px; cursor:pointer; }
.profile-circle { width:80px; height:80px; border-radius:50%; border:3px solid #9fa8ff; object-fit:cover; box-shadow:0 0 12px #9fa8ff88; background-color:#7a5fff22; transition:box-shadow .2s; }
.profile-pic-wrapper:hover .profile-circle { box-shadow:0 0 24px #ff9ecf88; background-color:#ff9ecf22; }
.profile-link { color:#9fa8ff; font-weight:bold; margin-top:8px; cursor:pointer; font-size:16px; text-decoration:underline; }
.menu-links { width:100%; display:flex; flex-direction:column; align-items:flex-start; }
.menu-links a { color:#ccc; padding:12px 20px; text-decoration:none; width:100%; font-weight:600; margin-bottom:7px; display:block; }
.menu-links a:hover, .menu-links a.active { background-color:#282838; color:#9fa8ff; }
.main { margin-left:220px; padding:30px; }
.card { background-color:#1e1e1e; border:1px solid #333; border-radius:10px; padding:20px; margin-bottom:20px; box-shadow:0 4px 15px rgba(255,255,255,0.05); }
.card h3 { color:#ff9ecf; margin-bottom:10px; }
.card p { color:#ccc; line-height:1.5; font-size:14px; }
.upload-btn { background: linear-gradient(90deg,#ff9ecf,#9fa8ff); border:none; padding:12px 20px; border-radius:5px; color:#121212; font-weight:bold; text-decoration:none; display:inline-block; }
.welcome-box { background:linear-gradient(135deg,#7a5fff,#ff6eab); border-radius:12px; box-shadow:inset 0 0 15px #ff9ecf88; padding:40px 30px; color:white; text-align:center; font-size:1.25rem; font-weight:600; }
#profileModal { display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background-color:rgba(0,0,0,0.85); justify-content:center; align-items:center; }
#profileModal img { width:50vw; height:50vw; border-radius:50%; object-fit:cover; box-shadow:0 0 25px #ff9ecf; }
</style>
</head>
<body>

<div class="sidebar" role="navigation" aria-label="Sidebar Navigation">
    <div class="dashboard-title">📘 Dashboard</div>
    <div class="profile-pic-wrapper" id="profilePicWrapper" tabindex="0" role="button" aria-label="View Profile Picture">
        <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Picture" class="profile-circle" />
    </div>
    <a href="profile.php" class="profile-link">Profile</a>
    <nav class="menu-links" aria-label="Main Menu">
        <a href="home.php">Home</a>
        <a href="my_summaries.php">My Summaries</a>
        <a href="search.php">Search New</a>
        <a href="account.php">Account Settings</a>
        <a href="logout.php">Logout</a>
    </nav>
</div>

<main class="main" role="main">
    <h1>Welcome back, <?= htmlspecialchars($_SESSION['user']['fname'] ?? $_SESSION['user']['email']) ?>!</h1>

    <section class="card" aria-labelledby="latestSummaryTitle">
        <h3 id="latestSummaryTitle">Latest Summary</h3>
        <?php if ($latestSummary): ?>
            <p><?= nl2br(htmlspecialchars(truncate_text($latestSummary['summary'], 200))) ?></p>
            <a href="summary_result.php?id=<?= (int)$latestSummary['id'] ?>" class="upload-btn">Read Summary</a>
        <?php else: ?>
            <p>No summaries available yet.</p>
        <?php endif; ?>
    </section>

    <section class="welcome-box" aria-live="polite">
        <span style="display:block;font-size:3rem;margin-bottom:10px;">📚</span>
        Ready to create new knowledge? Upload your books and get concise summaries instantly!
    </section>
</main>

<div id="profileModal" aria-hidden="true" tabindex="-1">
    <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Picture Large" />
</div>

<script>
const profilePicWrapper = document.getElementById('profilePicWrapper');
const profileModal = document.getElementById('profileModal');
profilePicWrapper.addEventListener('click', () => {
    profileModal.style.display = 'flex';
    profileModal.setAttribute('aria-hidden', 'false');
    profileModal.focus();
});
profileModal.addEventListener('click', (event) => {
    if (event.target === profileModal) {
        profileModal.style.display = 'none';
        profileModal.setAttribute('aria-hidden', 'true');
    }
});
document.addEventListener('keydown', (event) => {
    if (event.key === "Escape" && profileModal.style.display === 'flex') {
        profileModal.style.display = 'none';
        profileModal.setAttribute('aria-hidden', 'true');
    }
});

// reload if page loaded from bfcache (back button) to avoid showing protected page after logout
window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && window.performance.getEntriesByType('navigation')[0].type === 'back_forward')) {
        window.location.reload();
    }
});
</script>
</body>
</html>
