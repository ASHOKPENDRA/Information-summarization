<?php
session_start();
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pwd = $_POST['pwd'] ?? '';

    if (empty($email) || empty($pwd)) {
        $message = 'Please enter both email and password.';
    } else {
        $con = new mysqli("sql105.infinityfree.com", "if0_39907292", "6QecRDL6dargJVf", "if0_39907292_user");
        if ($con->connect_error) {
            $message = "Connection failed: " . $con->connect_error;
        } else {
            $stmt = $con->prepare("SELECT fname, email, pwd, profile_image FROM userTable WHERE email=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Plain check (keeps your existing DB behavior). To use hashed passwords, use password_verify.
                if ($pwd === $row['pwd']) {
                    // Save user in session (consistent structure)
                    $_SESSION['user'] = [
                        'fname' => $row['fname'],
                        'email' => $row['email'],
                        'profile_image' => $row['profile_image'] ?: 'assets/default_profile.png'
                    ];
                    $_SESSION['LAST_ACTIVITY'] = time();
                    $stmt->close();
                    $con->close();
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $message = "Invalid email or password.";
                }
            } else {
                $message = "Invalid email or password.";
            }
            $stmt->close();
        }
        $con->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | E-Book Summarization</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background-color:#121212;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
.container{background-color:#1e1e1e;padding:30px;border-radius:12px;width:380px;box-shadow:0 4px 15px rgba(255,255,255,0.05);}
.gradient-text{text-align:center;font-size:22px;font-weight:bold;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:20px;}
.gradient-label{display:block;margin-top:12px;font-size:14px;font-weight:500;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
input{width:100%;padding:10px;margin-top:6px;background-color:#2a2a2a;border:1px solid #444;border-radius:5px;color:#fff;}
input:focus{border-color:#9fa8ff;outline:none;}
button{width:100%;padding:12px;margin-top:20px;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);color:#121212;font-weight:bold;border:none;border-radius:5px;font-size:16px;cursor:pointer;}
button:hover{opacity:0.9;}
.signup-link{text-align:center;margin-top:14px;font-size:14px;color:#ccc;}
.signup-link a{background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;}
#message{margin-top:12px;text-align:center;font-size:14px;color:#ff9ecf;}
</style>
</head>
<body>
<div class="container">
  <h2 class="gradient-text">Login to E-Book Summarization</h2>

  <?php if ($message): ?>
    <p id="message"><?= htmlspecialchars($message) ?></p>
  <?php else: ?>
    <p id="message"></p>
  <?php endif; ?>

  <form method="post" action="" novalidate>
    <label class="gradient-label" for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label class="gradient-label" for="pwd">Password</label>
    <input type="password" id="pwd" name="pwd" required>

    <button type="submit">Login</button>
  </form>

  <p class="signup-link">Don't have an account? <a href="reg.php">Sign up</a></p>
</div>
</body>
</html>
