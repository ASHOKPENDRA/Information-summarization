<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
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

$message = '';
$successUpdate = false;
$addAccountMessage = '';

$userEmail = $_SESSION['user']['email'];
$newPasswordValue = '';
$confirmPasswordValue = '';

if (!isset($_SESSION['saved_accounts'])) {
    $_SESSION['saved_accounts'] = [];
}

// Delete account handler
if (isset($_GET['delete_account'])) {
    $delEmail = $_GET['delete_account'];
    if ($delEmail !== $userEmail) {
        $_SESSION['saved_accounts'] = array_filter($_SESSION['saved_accounts'], function ($acc) use ($delEmail) {
            return $acc['email'] !== $delEmail;
        });
        $addAccountMessage = "Account removed successfully.";
    } else {
        $addAccountMessage = "Cannot delete the current logged-in account.";
    }
}

// Add account handler
if (isset($_POST['add_account'])) {
    $addEmail = trim($_POST['add_email'] ?? '');
    $addPwd = trim($_POST['add_password'] ?? '');

    if ($addEmail && $addPwd) {
        $stmt = $con->prepare("SELECT fname, email, role, profile_image FROM userTable WHERE email=? AND pwd=?");
        $stmt->bind_param("ss", $addEmail, $addPwd);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $accountData = $result->fetch_assoc();
            $exists = false;
            foreach ($_SESSION['saved_accounts'] as $acc) {
                if ($acc['email'] === $addEmail) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $_SESSION['saved_accounts'][] = $accountData;
                $addAccountMessage = "Account added successfully.";
            } else {
                $addAccountMessage = "Account already added.";
            }
        } else {
            $addAccountMessage = "Invalid email or password.";
        }
        $stmt->close();
    } else {
        $addAccountMessage = "Enter email and password to add account.";
    }
}

// Switch account handler
if (isset($_GET['switch_account'])) {
    $switchEmail = $_GET['switch_account'];
    foreach ($_SESSION['saved_accounts'] as $acc) {
        if ($acc['email'] === $switchEmail) {
            $_SESSION['user'] = $acc;
            header("Location: dashboard.php");
            exit();
        }
    }
}

function isStrongPassword($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

// Account update handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['add_account'])) {
    $fname = trim($_POST['fname'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $passwordChanged = false;

    if ($currentPassword || $newPassword || $confirmPassword) {
        $stmt = $con->prepare("SELECT pwd FROM userTable WHERE email = ?");
        $stmt->bind_param("s", $userEmail);
        $stmt->execute();
        $stmt->bind_result($passwordInDb);
        $stmt->fetch();
        $stmt->close();

        if ($currentPassword !== $passwordInDb) {
            $message = "Current password is incorrect.";
            $newPasswordValue = '';
            $confirmPasswordValue = '';
        } elseif ($newPassword !== $confirmPassword) {
            $message = "New password and confirmation do not match.";
            $newPasswordValue = '';
            $confirmPasswordValue = '';
        } elseif (!isStrongPassword($newPassword)) {
            $message = "Enter a strong new password (min 8 chars, upper, lower, digit, special char).";
            $newPasswordValue = '';
            $confirmPasswordValue = '';
        } else {
            $passwordChanged = true;
        }
    }

    if (!$message) {
        if ($passwordChanged) {
            $stmt = $con->prepare("UPDATE userTable SET fname=?, bio=?, pwd=? WHERE email=?");
            $stmt->bind_param("ssss", $fname, $bio, $newPassword, $userEmail);
        } else {
            $stmt = $con->prepare("UPDATE userTable SET fname=?, bio=? WHERE email=?");
            $stmt->bind_param("sss", $fname, $bio, $userEmail);
        }

        if ($stmt->execute()) {
            $_SESSION['user']['fname'] = $fname;
            $_SESSION['user']['bio'] = $bio;
            $successUpdate = true;
            $newPasswordValue = '';
            $confirmPasswordValue = '';
        } else {
            $message = "Failed to update account: " . $stmt->error;
        }
        $stmt->close();
    }
}

$stmt = $con->prepare("SELECT fname, bio FROM userTable WHERE email = ?");
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$stmt->bind_result($dbFname, $dbBio);
$stmt->fetch();
$stmt->close();
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Account Settings</title>
<style>
*{box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background-color:#121212;color:#fff;margin:0;padding:40px 20px;display:flex;justify-content:center;}
.container{max-width:600px;background-color:#1e1e1e;padding:35px;border-radius:12px;box-shadow:0 0 20px #7a5fff;}
h1{color:#9fa8ff;text-align:center;margin-bottom:30px;}
form label{display:block;margin-bottom:8px;font-weight:600;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
input[type="text"],textarea,input[type="password"],input[type="email"] {
  width: 100%;
  padding: 10px 14px;
  margin-bottom: 20px;
  border-radius: 8px;
  border: none;
  font-size: 1rem;
  background-color: #2a2a2a;
  color: #fff;
  box-shadow: inset 1px 1px 4px #444;
  transition: box-shadow 0.3s ease;
  resize: vertical;
  box-sizing: border-box;
}
input[type="text"]:focus,textarea:focus,input[type="password"]:focus,input[type="email"]:focus {
  outline: none;
  box-shadow: 0 0 10px #9fa8ff, inset 1px 1px 4px #444;
}
.input-container {
  position: relative;
  margin-bottom: 20px;
}
input[type="password"],input[type="text"] {
  padding-right: 40px;
}
.toggle-password-icon {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #9fa8ff;
  font-size: 18px;
  user-select: none;
  z-index: 10;
}
.toggle-password-icon.active {
  color: #ff9ecf;
}
button {
  width: 100%;
  padding: 16px;
  background: linear-gradient(90deg, #ff9ecf,#9fa8ff);
  border-radius: 12px;
  border: none;
  font-weight: 600;
  font-size: 1.1rem;
  color: #121212;
  cursor: pointer;
  box-shadow: 0 0 20px #7a5fff;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}
button:hover {
  background-color: #5749ff;
  color: #fff;
  transition: background-color 0.3s ease;
}
.message {
  text-align: center;
  font-weight: 600;
  color: #ff9ecf;
  margin-bottom: 20px;
}
a.back-link,a.login-other {
  display: block;
  text-align: center;
  font-weight: 600;
  text-decoration: none;
  margin-top: 25px;
}
a.back-link {
  color: #9fa8ff;
}
a.back-link:hover {
  text-decoration: underline;
}
a.login-other {
  color: #ff9ecf;
}
a.login-other:hover {
  text-decoration: underline;
}
#changePasswordToggle {
  display: block;
  width: fit-content;
  margin: 15px auto 20px auto;
  cursor: pointer;
  font-weight: 600;
  color: #9fa8ff;
  user-select: none;
}
#changePasswordToggle:hover {
  text-decoration: underline;
}
#changePasswordFields {
  display: none;
}
#toggleAddAccount {
  color: #ff9ecf;
  font-weight: 600;
  cursor: pointer;
  text-align: center;
  margin-top: 40px;
}
#addAccountSection {
  display: none;
  margin-top: 25px;
}
</style>
</head>
<body>

<div class="container">
<h1>Account Settings</h1>

<?php if ($message): ?>
  <div class="message"><?= htmlspecialchars($message) ?></div>
<?php elseif ($successUpdate): ?>
  <div class="message">Account updated successfully.</div>
<?php endif; ?>

<form action="account.php" method="post" novalidate id="accountForm">
  <label for="fname">Full Name</label>
  <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($dbFname ?? '') ?>" required />

  <label for="bio">Bio</label>
  <textarea id="bio" name="bio" rows="5"><?= htmlspecialchars($dbBio ?? '') ?></textarea>

  <span id="changePasswordToggle">Change Password</span>

  <div id="changePasswordFields">
    <label for="current_password">Current Password</label>
    <div class="input-container">
      <input type="password" id="current_password" name="current_password" autocomplete="current-password" />
      <span class="toggle-password-icon" data-target="current_password" title="Show/Hide current password">👁</span>
    </div>

    <label for="new_password">New Password</label>
    <div class="input-container">
      <input type="password" id="new_password" name="new_password" value="<?= htmlspecialchars($newPasswordValue) ?>" autocomplete="new-password" />
      <span class="toggle-password-icon" data-target="new_password" title="Show/Hide new password">👁</span>
    </div>

    <label for="confirm_password">Confirm New Password</label>
    <div class="input-container">
      <input type="password" id="confirm_password" name="confirm_password" value="<?= htmlspecialchars($confirmPasswordValue) ?>" autocomplete="new-password" />
      <span class="toggle-password-icon" data-target="confirm_password" title="Show/Hide confirm password">👁</span>
    </div>
  </div>

  <button type="submit">Update Account</button>
</form>

<a href="dashboard.php" class="back-link">← Back to Dashboard</a>

<p id="toggleAddAccount">Add Account</p>

<div id="addAccountSection">
  <?php if ($addAccountMessage): ?>
  <p class="message"><?= htmlspecialchars($addAccountMessage) ?></p>
  <?php endif; ?>
  <form method="post" action="account.php" novalidate>
    <label for="add_email" style="background: linear-gradient(90deg, #ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Email</label>
    <input type="email" id="add_email" name="add_email" style="width:100%;padding:8px;border-radius:6px;margin-bottom:8px;background:#2a2a2a;color:#fff;border:none;" required />
    <label for="add_password" style="background: linear-gradient(90deg, #ff9ecf,#9fa8ff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Password</label>
    <input type="password" id="add_password" name="add_password" style="width:100%;padding:8px;border-radius:6px;margin-bottom:8px;background:#2a2a2a;color:#fff;border:none;" required />
    <button type="submit" name="add_account" style="padding:10px 20px;background:linear-gradient(90deg,#ff9ecf,#9fa8ff);border:none;border-radius:8px;cursor:pointer;">Add Account</button>
  </form>
</div>

<a href="loginpage.php" class="login-other">Login to other account</a>
</div>
<script>
document.getElementById('changePasswordToggle').addEventListener('click', function () {
    const fields = document.getElementById('changePasswordFields');
    if (fields.style.display === 'block') {
        fields.style.display = 'none';
    } else {
        fields.style.display = 'block';
    }
});

document.getElementById('toggleAddAccount').addEventListener('click', function () {
    const section = document.getElementById('addAccountSection');
    if (section.style.display === 'block') {
        section.style.display = 'none';
    } else {
        section.style.display = 'block';
    }
});

let visibleInputId = null;
document.querySelectorAll('.toggle-password-icon').forEach(icon => {
    icon.addEventListener('click', () => {
        const target = icon.dataset.target;
        const input = document.getElementById(target);

        if (input.type === 'password') {
            if (visibleInputId && visibleInputId !== target) {
                const prevInput = document.getElementById(visibleInputId);
                prevInput.type = 'password';
                document.querySelector(`.toggle-password-icon[data-target="${visibleInputId}"]`).classList.remove('active');
            }
            input.type = 'text';
            icon.classList.add('active');
            visibleInputId = target;
        } else {
            input.type = 'password';
            icon.classList.remove('active');
            visibleInputId = null;
        }
    });
});
</script>
</body>
</html>
