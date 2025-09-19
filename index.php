<?php
require_once __DIR__ . '/db.php';
$stmt = $conn->prepare("SELECT id, title, body, created_at FROM notices WHERE is_active=1 ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Digital Notice Board</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Digital Notice Board</h1></header>
<main>
  <?php if ($result->num_rows === 0): ?>
    <p class="empty">No active notices.</p>
  <?php else: ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <article class="notice">
        <h2><?= htmlspecialchars($row['title']) ?></h2>
        <time datetime="<?= $row['created_at'] ?>">
          <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
        </time>
        <p><?= nl2br(htmlspecialchars($row['body'])) ?></p>
      </article>
    <?php endwhile; ?>
  <?php endif; ?>
</main>
<footer><a href="admin.php">Admin Login</a></footer>
</body>
</html>
<?php
$stmt->close();
$conn->close();
