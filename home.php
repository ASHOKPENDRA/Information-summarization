<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: loginpage.php");
  exit();
}
$user = htmlspecialchars($_SESSION['user']['fname']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - E-Book Summarization</title>
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
    header h1 {
      font-size: 28px;
    }
    .profile-section {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .profile-section img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
    .logout-btn {
      background-color: transparent;
      border: 2px solid white;
      color: white;
      font-weight: bold;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #e74c3c;
      border-color: #e74c3c;
    }
    .hero {
      text-align: center;
      padding: 60px 20px;
      background-image: url('https://images.unsplash.com/photo-1512820790803-83ca734da794');
      background-size: cover;
      background-position: center;
      color: white;
    }
    .hero h2 {
      font-size: 36px;
      margin-bottom: 10px;
    }
    .hero p {
      font-size: 18px;
    }
    .categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      padding: 40px;
    }
    .category {
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .category:hover {
      transform: translateY(-5px);
    }
    .category img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .category h3 a {
      color: #2c3e50;
      text-decoration: none;
      font-weight: bold;
    }
    .category h3 a:hover {
      color: #978b8a;
    }
    footer {
      text-align: center;
      padding: 20px;
      background-color: #ecf0f1;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <header>
    <h1>📘 E-Book Summarization Dashboard</h1>
    <div class="profile-section">
      <img src="<?php echo htmlspecialchars($_SESSION['user']['profile_image'] ?? 'https://i.pravatar.cc/40'); ?>" alt="Profile" />
      <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
    </div>
  </header>

  <section class="hero">
    <h2>Welcome back, <?php echo $user; ?> </h2>
    <p>Continue exploring summaries of top books across genres.</p>
  </section>

  <section class="categories">
    <div class="category">
      <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f" alt="Science" />
      <h3><a href="category.php">Coding & Technology</a></h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353" alt="Fiction" />
      <h3><a href="fiction.php">Fiction & Literature</a></h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1553729459-efe14ef6055d" alt="Business" />
      <h3><a href="business.php">Business & Economics</a></h3>
    </div>
    <div class="category">
      <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b" alt="Self Help" />
      <h3><a href="selfhelp.php">Self-Help & Motivation</a></h3>
    </div>
  </section>

  <footer>
    &copy; 2025 E-Book Summarizer Dashboard. All rights reserved.
  </footer>

  <script>
    // Prevent going back after logout
    if (window.history && window.history.pushState) {
      window.history.pushState(null, "", window.location.href);
      window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
      };
    }
  </script>
</body>
</html>
