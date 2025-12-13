<?php
session_start();
require_once 'db.php';
$errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $pwd = $_POST['pwd'] ?? '';
    $role = $_POST['role'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    $emailPattern = '/^[a-z0-9]+([._%+-]?[a-z0-9]+)*@[a-z0-9]+([.-]?[a-z0-9]+)*\.[a-z]{2,}$/';

    function isStrongPassword($password) {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
    }

    if (!$fname || !$email || !$pwd || !$confirmPassword || !$role) {
        $errorMsg = "All fields are required.";
    } elseif (!preg_match($emailPattern, $email) || $email !== strtolower($email)) {
        $errorMsg = "Invalid email format.";
    } elseif ($pwd !== $confirmPassword) {
        $errorMsg = "Passwords do not match.";
    } elseif (!isStrongPassword($pwd)) {
        $errorMsg = "Enter a strong password (min 8 chars, upper, lower, digit, special char).";
    } else {
        $servername = "sql105.infinityfree.com";
        $username = "if0_39907292";
        $passwordDb = "6QecRDL6dargJVf";
        $database = "if0_39907292_user";
        $con = new mysqli($servername, $username, $passwordDb, $database);

        if ($con->connect_error) {
            $errorMsg = "Connection failed: " . $con->connect_error;
        } else {
            $check = $con->prepare("SELECT email FROM userTable WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $errorMsg = "Email already registered. Try logging in.";
            } else {
                $check->close();
                $stmt = $con->prepare("INSERT INTO userTable (fname, email, pwd, role) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $fname, $email, $pwd, $role);
                if ($stmt->execute()) {
                    $_SESSION['user'] = [
                        'fname' => $fname,
                        'email' => $email,
                        'role' => $role
                    ];
                    $stmt->close();
                    $con->close();
                    header("Location: dashboard.php");
                    exit;
                } else {
                    $errorMsg = "Error: Could not register. Try again later.";
                }
                $stmt->close();
            }
            $con->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Register - E-Book Summarization</title>
<style>
*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background-color:#121212;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}
.container{background-color:#1e1e1e;padding:30px;border-radius:12px;box-shadow:0 4px 15px rgba(255,255,255,0.05);width:380px;}
.gradient-text{text-align:center;font-size:22px;font-weight:bold;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:25px;}
.gradient-label{display:block;margin-top:15px;font-size:14px;font-weight:500;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
input, select{width:100%;padding:10px;margin-top:5px;background-color:#2a2a2a;border:1px solid #444;border-radius:5px;color:#ffffff;}
input:focus, select:focus{border-color:#9fa8ff;outline:none;}
button{width:100%;padding:12px;margin-top:20px;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);color:#121212;font-weight:bold;border:none;border-radius:5px;font-size:16px;cursor:pointer;transition:opacity 0.3s ease;}
button:hover{opacity:0.85;}
.login-link{text-align:center;margin-top:15px;font-size:14px;color:#ccc;}
.login-link a{background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-decoration:none;}
#message{margin-top:15px;text-align:center;font-size:14px;color:#ff9ecf;}
</style>
</head>
<body>
<div class="container">
<form id="registrationForm" method="POST" autocomplete="off">
  <h2 class="gradient-text">Register for E-Book Summarization</h2>
  <label class="gradient-label" for="fname">Full Name</label>
  <input type="text" id="fname" name="fname" required/>
  <label class="gradient-label" for="email">Email</label>
  <input type="email" id="email" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"/>
  <label class="gradient-label" for="pwd">Password</label>
  <input type="password" id="pwd" name="pwd" required minlength="8"/>
  <label class="gradient-label" for="confirmPassword">Confirm Password</label>
  <input type="password" id="confirmPassword" name="confirmPassword" required minlength="8"/>
  <label class="gradient-label" for="role">User Role</label>
  <select id="role" name="role" required>
    <option value="">--Select Role--</option>
    <option value="student">Student</option>
    <option value="teacher">Teacher</option>
    <option value="researcher">Researcher</option>
  </select>
  <button type="submit">Register</button>
  <p class="login-link">Already have an account? <a href="loginpage.php">Login</a></p>
  <p id="message"><?php if ($errorMsg) echo htmlspecialchars($errorMsg); ?></p>
</form>
</div>
<script>
document.getElementById('email').addEventListener('input', function() {
  this.value = this.value.replace(/[^a-z0-9@._%+-]/g, '').toLowerCase();
});
document.getElementById('registrationForm').addEventListener('submit',function(e){
  const fullName = document.getElementById('fname').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('pwd').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  const role = document.getElementById('role').value;
  const message = document.getElementById('message');
  const emailPattern = /^[a-z0-9]+([._%+-]?[a-z0-9]+)*@[a-z0-9]+([.-]?[a-z0-9]+)*\.[a-z]{2,}$/;
  const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

  if (!fullName || !email || !password || !confirmPassword || !role) {
    e.preventDefault(); message.style.color = '#ff9ecf'; message.textContent = 'All fields are required.'; return;
  }
  if (!emailPattern.test(email)) {
    e.preventDefault(); message.style.color = '#ff9ecf'; message.textContent = 'Invalid email format.'; return;
  }
  if (email !== email.toLowerCase()) {
    e.preventDefault();
    message.style.color = '#ff9ecf';
    message.textContent = 'Email must be all lowercase.';
    return;
  }
  if (password !== confirmPassword) {
    e.preventDefault(); message.style.color = '#ff9ecf'; message.textContent = 'Passwords do not match.'; return;
  }
  if (!strongPassword.test(password)) {
    e.preventDefault();
    message.style.color = '#ff9ecf';
    message.textContent = 'Enter a strong password (min 8 chars, upper, lower, digit, special char).';
    return;
  }
});
</script>
</body>
</html>
