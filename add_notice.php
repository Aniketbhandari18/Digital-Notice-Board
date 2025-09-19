<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin.php'); exit; }
require_once __DIR__ . '/db.php';

// Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
  $title = trim($_POST['title'] ?? '');
  $body  = trim($_POST['body'] ?? '');
  if ($title !== '' && $body !== '') {
    $stmt = $conn->prepare("INSERT INTO notices(title, body, is_active) VALUES(?, ?, 1)");
    $stmt->bind_param('ss', $title, $body);
    $stmt->execute();
    $stmt->close();
    header('Location: add_notice.php'); exit;
  }
}

// Toggle
if (isset($_GET['toggle'])) {
  $id = (int) $_GET['toggle'];
  $conn->query("UPDATE notices SET is_active = NOT is_active WHERE id = $id");
  header('Location: add_notice.php'); exit;
}

$notices = $conn->query("SELECT id, title, created_at, is_active FROM notices ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Notices</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Manage Notices</h1><nav><a href="logout.php">Logout</a> | <a href="index.php">View Site</a></nav></header>

<section class="new-notice">
  <h2>Create Notice</h2>
  <form method="post">
    <input name="title" placeholder="Title" required>
    <textarea name="body" rows="4" placeholder="Notice body" required></textarea>
    <button type="submit" name="create" value="1">Publish</button>
  </form>
</section>

<section>
  <h2>Existing Notices</h2>
  <table>
    <thead><tr><th>Title</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      <?php while ($n = $notices->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($n['title']) ?></td>
        <td><?= date('d M Y H:i', strtotime($n['created_at'])) ?></td>
        <td><?= $n['is_active'] ? 'Active' : 'Hidden' ?></td>
        <td>
          <a href="edit_notice.php?id=<?= $n['id'] ?>">Edit</a> |
          <a href="?toggle=<?= $n['id'] ?>">Toggle</a> |
          <a href="delete_notice.php?id=<?= $n['id'] ?>" onclick="return confirm('Delete this notice?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>
</body>
</html>
<?php $conn->close(); ?>
