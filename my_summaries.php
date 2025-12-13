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

$user_email = $_SESSION['user']['email'];

// fetch all summaries for this user
$stmt = $con->prepare("SELECT id, summary, created_at FROM summaries WHERE user_email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Summaries</title>
<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    margin: 0;
    padding: 0;
    background: #121212;
    color: #fff;
}

.container {
    max-width: 1000px;
    margin: 40px auto;
    background: #1f1f35;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 25px rgba(0,0,0,0.5);
}

h1 {
    text-align: center;
    color: #ff9ecf;
    font-size: 2rem;
    margin-bottom: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: #2b264b;
    border-radius: 10px;
    overflow: hidden;
}

th, td {
    padding: 15px 12px;
    border-bottom: 1px solid rgba(255,255,255,0.15);
    text-align: left;
    vertical-align: top;
}

th {
    background: linear-gradient(90deg, #ff4fa1, #7a5fff);
    color: #fff;
    font-weight: 600;
    font-size: 16px;
}

tr:hover {
    background-color: rgba(255,158,207,0.1);
}

.btn {
    background: linear-gradient(90deg, #ff4fa1, #7a5fff);
    color: #121212;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 6px;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    opacity: 0.9;
    box-shadow: 0 0 12px rgba(159,168,255,0.6);
}

.back {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    background: linear-gradient(90deg, #ff4fa1, #7a5fff);
    color: #121212;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.back:hover {
    opacity: 0.9;
    box-shadow: 0 0 12px rgba(159,168,255,0.6);
}

@media (max-width: 700px) {
    th, td { padding: 10px 8px; font-size: 14px; }
    h1 { font-size: 1.6rem; }
    .btn, .back { padding: 8px 16px; font-size: 14px; }
}
</style>
</head>
<body>
<div class="container">
<h1>📚 My Summaries</h1>

<?php if ($result && $result->num_rows > 0): ?>
<table>
    <tr>
        <th>Title</th>
        <th>Date & Time</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): 
        $lines = explode("\n", $row['summary']);
        $title = "Untitled Summary";
        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $title = trim($line);
                break;
            }
        }

        $date = new DateTime($row['created_at'], new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
    ?>
    <tr>
        <td><?= htmlspecialchars($title) ?></td>
        <td><?= $date->format('d M Y, h:i A') ?></td>
        <td>
            <form action="summary_result.php" method="get">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn">View</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
<p style="text-align:center; font-size:1.1rem;">No summaries found.</p>
<?php endif; ?>

<a href="dashboard.php" class="back">← Back to Dashboard</a>
</div>
</body>
</html>
