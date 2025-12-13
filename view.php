<?php
include 'header.php';
include 'db.php';

$userEmail = $_SESSION['user']['email'];
$sql = "SELECT id, summary, created_at FROM summaries WHERE user_email=? ORDER BY id DESC";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
?>
<h2>📚 My Saved Summaries</h2>
<table style="width:100%; border-collapse: collapse; background:#1e1e1e;">
  <tr>
    <th style="padding:10px; border:1px solid #333;">Date</th>
    <th style="padding:10px; border:1px solid #333;">Summary</th>
    <th style="padding:10px; border:1px solid #333;">Action</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($row['created_at']) ?></td>
    <td><?= htmlspecialchars(substr($row['summary'], 0, 200)) ?>...</td>
    <td>
      <a href="summary_result.php?id=<?= $row['id'] ?>" class="upload-btn">View</a>
      <a href="delete_summary.php?id=<?= $row['id'] ?>" class="upload-btn" style="background:red;">Delete</a>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
</div>
</body>
</html>
