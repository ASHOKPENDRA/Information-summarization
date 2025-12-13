<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Smart Summarizer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #f1f1f1;
      padding: 40px 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }
    .container {
      max-width: 640px;
      width: 100%;
      background: rgba(20, 20, 30, 0.9);
      padding: 30px 34px 36px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(155, 89, 182, 0.6);
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: stretch;
    }
    h1.title {
      text-align: center;
      font-weight: 700;
      font-size: 2.2rem;
      margin-bottom: 36px;
      background: linear-gradient(90deg, #ff4fa1, #7a5fff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      user-select: none;
    }
    label.gradient-label {
      font-weight: 600;
      color: #f1f1f1;
      font-size: 15px;
      margin-top: 20px;
      margin-bottom: 8px;
      background: linear-gradient(90deg, #ff4fa1, #7a5fff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      user-select: none;
      display: block;
    }
    textarea#bookText {
      width: 100%;
      height: 230px;
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 10px;
      padding: 16px 18px;
      color: #ddd;
      font-size: 15px;
      resize: vertical;
      font-family: 'Segoe UI', Arial, sans-serif;
      box-sizing: border-box;
      transition: border-color 0.25s ease;
    }
    textarea#bookText:focus {
      border-color: #7a5fff;
      outline: none;
    }
    input[type="file"] {
      width: 100%;
      padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid #444;
      background: #2a2a2a;
      color: #ccc;
      font-size: 14px;
      cursor: pointer;
      box-sizing: border-box;
      transition: border-color 0.25s ease;
      margin-top: 6px;
    }
    input[type="file"]:focus {
      border-color: #7a5fff;
      outline: none;
    }
    button.summarize-btn {
      width: 100%;
      background: linear-gradient(90deg, #ff4fa1, #7a5fff);
      border: none;
      border-radius: 12px;
      color: #121212;
      font-weight: 700;
      font-size: 16px;
      padding: 14px 0;
      margin-top: 26px;
      cursor: pointer;
      transition: background 0.3s ease;
      user-select: none;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
    }
    button.summarize-btn:hover {
      background: linear-gradient(90deg, #e03a8b, #645bcf);
    }
    #message {
      margin-top: 20px;
      font-size: 15px;
      color: #ff4fa1;
      min-height: 20px;
      text-align: center;
      font-weight: 600;
    }
    .bottom-buttons {
      margin-top: 22px;
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }
    a.btn {
      background: linear-gradient(90deg, #ff4fa1, #7a5fff);
      padding: 12px 36px;
      border-radius: 12px;
      color: #121212;
      font-weight: 700;
      text-decoration: none;
      font-size: 16px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      user-select: none;
      transition: background 0.3s ease;
    }
    a.btn:hover {
      background: linear-gradient(90deg, #e03a8b, #645bcf);
    }
    @media (max-width: 600px) {
      .container {
        padding: 24px 28px 30px;
      }
      h1.title {
        font-size: 1.8rem;
        margin-bottom: 28px;
      }
      textarea#bookText {
        height: 170px;
        font-size: 14px;
      }
      button.summarize-btn, a.btn {
        font-size: 14px;
        padding: 12px 28px;
      }
      .bottom-buttons {
        flex-direction: column;
        gap: 14px;
      }
    }
  </style>
</head>
<body>
  <main class="container" role="main" aria-labelledby="title">
    <h1 id="title" class="title"> Upload or Paste Text to Summarize</h1>
    <form id="summarizeForm" method="post" action="upload.php" enctype="multipart/form-data" novalidate>
      <label class="gradient-label" for="bookText">Paste your text below</label>
      <textarea id="bookText" name="bookText" placeholder="Paste your text here (or upload a document below)"></textarea>

      <label class="gradient-label" for="bookFile">Or upload a file (.txt, .pdf, .doc, .docx)</label>
      <input type="file" id="bookFile" name="bookFile" accept=".txt,.pdf,.doc,.docx" aria-describedby="message">

      <button type="submit" class="summarize-btn" aria-label="Summarize Text"><i class="fas fa-magic"></i> Summarize</button>

      <p id="message" aria-live="polite"></p>
    </form>
    <div class="bottom-buttons">
      <a href="my_summaries.php" class="btn" aria-label="My Summaries"><i class="fas fa-file-alt"></i> My Summaries</a>
      <a href="dashboard.php" class="btn" aria-label="Back to Dashboard"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
  </main>

  <script>
    const fileInput = document.getElementById('bookFile');
    const textArea = document.getElementById('bookText');
    const message = document.getElementById('message');

    fileInput.addEventListener('change', (event) => {
      message.textContent = '';
      const file = event.target.files[0];
      if (!file) return;

      const ext = file.name.split('.').pop().toLowerCase();
      const reader = new FileReader();

      if (ext === 'txt') {
        reader.onload = e => textArea.value = e.target.result;
        reader.readAsText(file);

      } else if (ext === 'pdf') {
        reader.onload = async e => {
          try {
            const typedarray = new Uint8Array(e.target.result);
            const pdf = await pdfjsLib.getDocument(typedarray).promise;
            let txt = "";
            for (let i = 1; i <= pdf.numPages; i++) {
              const page = await pdf.getPage(i);
              const content = await page.getTextContent();
              txt += content.items.map(s => s.str).join(" ") + "\n\n";
            }
            textArea.value = txt.trim();
          } catch {
            message.textContent = " Could not extract text from PDF.";
          }
        };
        reader.readAsArrayBuffer(file);

      } else if (ext === 'doc' || ext === 'docx') {
        reader.onload = async e => {
          try {
            const result = await mammoth.extractRawText({ arrayBuffer: e.target.result });
            textArea.value = result.value.trim();
          } catch {
            message.textContent = " Could not extract text from DOCX.";
          }
        };
        reader.readAsArrayBuffer(file);

      } else {
        message.textContent = " Unsupported file format! Please use TXT, PDF, DOC or DOCX.";
        fileInput.value = "";
      }
    });
  </script>
</body>
</html>
