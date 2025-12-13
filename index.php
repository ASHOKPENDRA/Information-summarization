<?php
session_start();
$message = '';
// if already logged in redirect to dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f8;
      color: #333;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #2c3e50;
      color: white;
      padding: 20px 40px;
    }
    header h1 { font-size: 28px; }
    .auth-buttons { display:flex; gap:15px; }
    .auth-buttons a {
      color:white; text-decoration:none; font-weight:bold;
      padding:8px 16px; border:2px solid white; border-radius:4px;
    }
    .auth-buttons a:hover { background-color:#e74c3c; border-color:#e74c3c; }
    .hero {
      text-align:center; padding:60px 20px;
      background-image: url('https://images.unsplash.com/photo-1512820790803-83ca734da794');
      background-size: cover; background-position:center; color:white;
    }
    .categories { display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:30px; padding:40px; }
    .category { background:white; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); overflow:hidden; transition:transform .3s; }
    .category:hover { transform: translateY(-5px); }
    .category img { width:100%; height:180px; object-fit:cover; }
    .category h3 { padding:12px; color:#2c3e50; margin:0; }
    footer { text-align:center; padding:20px; background-color:#ecf0f1; font-size:14px; }
  </style>
</head>
<body>

  <header>
    <h1> E-Book Summarization</h1>
    <div class="auth-buttons">
      <a href="loginpage.php">Login</a>
      <a href="reg.php">Register</a>
    </div>
  </header>

  <section class="hero">
    <h2>Welcome to Your Smart Reading Companion</h2>
    <p>Explore summaries of top books across genres. Learn faster, read smarter.</p>
  </section>

  <section class="categories">
    <div class="category">
      <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f" alt="Science">
      <h3><a href="categories.php">Coding & Technology</a></h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353" alt="Fiction">
      <h3>Fiction & Literature</h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d" alt="Business">
      <h3>Business & Economics</h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b" alt="Self Help">
      <h3>Self-Help & Motivation</h3>
    </div>
  </section>

  <footer></footer>

</body>
</html>
