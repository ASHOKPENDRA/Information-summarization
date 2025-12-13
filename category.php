<?php
// category.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Coding Subjects</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4f8;
      color: #333;
    }

    header {
      text-align: center;
      background: #0077cc;
      color: white;
      padding: 2em 1em;
    }

    header h1 {
      margin: 0;
      font-size: 2.5em;
    }

    header p {
      font-size: 1.2em;
      margin-top: 0.5em;
    }

    .subject-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2em;
      padding: 2em;
    }

    .subject-card {
      background: white;
      border-radius: 10px;
      padding: 1.5em;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .subject-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.15);
    }

    .subject-card h2 {
      color: #0077cc;
      margin-top: 0;
    }

    .subject-card p {
      font-size: 1em;
      margin: 0.5em 0 1em;
    }

    .subject-card a {
      text-decoration: none;
      color: #0077cc;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <h1>Coding Subjects</h1>
    <p>Choose a topic to dive into and start learning!</p>
  </header>

  <main class="subject-grid">
    <div class="subject-card">
      <h2>Deep Learning</h2>
      <img src="https://mit-press-new-us.imgix.net/covers/9780262035613.jpg?auto=format&w=298" alt="Deep Learning Textbook by Ian Goodfellow" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Deep learning is a powerful subset of machine learning that mimics how the human brain processes information</p>
      <a href="dlbook.html">Explore → </a>
    </div>

    <div class="subject-card">
      <h2>Python</h2>
      <img src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1668218370i/63281342.jpg" alt="Python Fundamentals Textbook by Benjamin Bennett Alexander" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Learn the most beginner-friendly and powerful programming language.</p>
      <a href="#">Explore →</a>
    </div>

    <div class="subject-card">
      <h2>Web Development</h2>
      <img src="https://m.media-amazon.com/images/I/41+Bik0tUlL.jpg" alt="Web Development Textbook by White Belt Mastery" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Master HTML, CSS, JavaScript and build stunning websites.</p>
      <a href="#">Explore →</a>
    </div>

    <div class="subject-card">
      <h2>OBJECT ORIENTED PROGRAMMING THROUGH JAVA</h2>
      <img src="https://tse2.mm.bing.net/th/id/OIP.sUUPOsI7iTPoeEICqXdEWgAAAA?rs=1&pid=ImgDetMain&o=7&rm=3" alt="m.s manjunath" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Build scalable applications and understand object-oriented programming.</p>
      <a href="#">Explore →</a>
    </div>

    <div class="subject-card">
      <h2>Data Structures</h2>
      <img src="https://archive.org/services/img/datastructuresus0000tene/full/pct:200/0/default.jpg" alt="Data Structures using C by Tanenbaum, Langsam, Augenstein" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Understand how data is organized and manipulated efficiently.</p>
      <a href="#">Explore →</a>
    </div>

    <div class="subject-card">
      <h2>Machine Learning</h2>
      <img src="https://m.media-amazon.com/images/I/61qWAvARI6L._SL1237_.jpg" alt="Machine Learning Textbook by Stephen Marsland" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Discover how machines learn from data and make predictions.</p>
      <a href="#">Explore →</a>
    </div>

    <div class="subject-card">
      <h2>Cybersecurity</h2>
      <img src="https://m.media-amazon.com/images/I/61u9rNIvzAL.jpg" alt="Antony Stewart" style="width:100%; max-width:200px; display:block; margin-bottom:10px;" />
      <p>Protect systems and data from digital threats and vulnerabilities.</p>
      <a href="#">Explore →</a>
    </div>
  </main>
</body>
</html>
