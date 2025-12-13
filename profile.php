<?php
session_start();

if (!isset($_SESSION['user']['email'])) {
    header('Location: loginpage.php');
    exit();
}

$servername = "sql105.infinityfree.com";
$username = "if0_39907292";
$passwordDb = "6QecRDL6dargJVf";
$database = "if0_39907292_user";

$con = new mysqli($servername, $username, $passwordDb, $database);
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$user_email = $_SESSION['user']['email'];

// ✅ Handle profile image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $uploadDir = "uploads/";
    $fileName = basename($_FILES['profile_image']['name']);
    $targetFile = $uploadDir . uniqid() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allow only jpg, jpeg, png
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            // Update DB
            $update = $con->prepare("UPDATE userTable SET profile_image = ? WHERE email = ?");
            $update->bind_param("ss", $targetFile, $user_email);
            $update->execute();
            $update->close();

            // ✅ Update session immediately
            $_SESSION['user']['profile_image'] = $targetFile;

            // Refresh page to show updated image
            header("Location: profile.php?updated=1");
            exit();
        } else {
            $error = "Failed to upload the image. Please try again.";
        }
    } else {
        $error = "Only JPG, JPEG, and PNG files are allowed.";
    }
}

// ✅ Fetch user info
$query = "SELECT fname, email, profile_image, bio FROM userTable WHERE email = ?";
$stmt = $con->prepare($query);
$stmt->bind_param('s', $user_email);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$stmt->close();
$con->close();

if (!$userData) {
    die("User not found.");
}

$profile_image = $userData['profile_image'] ?: 'assets/default_profile.png';
$name = $userData['fname'] ?: "User";
$bio = $userData['bio'] ?: "This user has not added a bio yet.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Profile - E-Book Summarizer</title>
<style>
body {
    background: linear-gradient(135deg, #181825, #444 80%);
    color: #fff;
    margin: 0;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.container {
    max-width: 500px;
    margin: 60px auto 0;
    background: rgba(20,20,30,0.96);
    border-radius: 16px;
    padding: 35px 38px;
    box-shadow: 0 0 30px #7a5fff80;
    text-align: center;
}
.profile-img {
    width: 130px;
    height: 130px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #9fa8ff;
    box-shadow: 0 2px 30px #ff9ecfaa;
    margin-bottom: 10px;
}
h2 {
    color: #ff9ecf;
    margin-bottom: 18px;
}
.info {
    font-size: 1.04rem;
    margin-bottom: 16px;
    color: #f0f0f0;
}
.bio {
    font-style: italic;
    background-color: #282838;
    border-radius: 10px;
    padding: 15px;
    color: #ddd;
    margin-bottom: 16px;
    white-space: pre-wrap;
}
.btn {
    display: inline-block;
    background: linear-gradient(90deg, #ff9ecf, #9fa8ff);
    color: #181825;
    font-weight: bold;
    border: none;
    border-radius: 7px;
    padding: 11px 28px;
    font-size: 15px;
    text-decoration: none;
    box-shadow: 0 0 10px #9fa8ff55;
    cursor: pointer;
    transition: background 0.25s;
}
.btn:hover {
    background: linear-gradient(90deg, #9fa8ff, #ff9ecf);
}
.edit-form {
    margin-top: 15px;
}
input[type="file"] {
    display: none;
}
.upload-label {
    background: #9fa8ff;
    color: #000;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}
.upload-label:hover {
    background: #ff9ecf;
}
.msg {
    margin-top: 10px;
    color: #ff9ecf;
}
</style>
</head>
<body>
<div class="container">
    <img src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Image" class="profile-img" />
    <form class="edit-form" action="" method="POST" enctype="multipart/form-data">
        <label for="profile_image" class="upload-label">📷 Edit Profile Picture</label>
        <input type="file" name="profile_image" id="profile_image" accept=".jpg,.jpeg,.png" onchange="this.form.submit()">
    </form>

    <?php if (isset($error)): ?>
        <div class="msg"><?= htmlspecialchars($error) ?></div>
    <?php elseif (isset($_GET['updated'])): ?>
        <div class="msg">✅ Profile picture updated successfully!</div>
    <?php endif; ?>

    <h2><?= htmlspecialchars($name) ?></h2>
    <div class="info"><b>Email:</b> <?= htmlspecialchars($user_email) ?></div>
    <div class="bio"><?= nl2br(htmlspecialchars($bio)) ?></div>
    <a href="dashboard.php" class="btn">← Back to Dashboard</a>
</div>
</body>
</html>
