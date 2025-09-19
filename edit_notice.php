<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin.php'); exit; }
require_once __DIR__ . '/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: add_notice.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $body  = trim($_POST['body'] ?? '');
  $active = isset($_POST['is_active']) ? 1 : 0;

  if ($title !== '' && $body !== '') {
    $stmt = $conn->prepare("UPDATE notices SET title=?, body=?, is_active=? WHERE id=?");
    $stmt->bind_param('ssii', $title, $body, $active, $id);
    $stmt->execute();
    $stmt->close();
    header('Location: add_notice.php'); exit;
  }
}

$stmt = $conn->prepare("SELECT title, body, is_active FROM notices WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($title, $body, $is_active);
if (!$stmt->fetch()) { $stmt->close(); header('Location: add_notice.php'); exit; }
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Notice</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header><h1>Edit Notice</h1><nav><a href="add_notice.php">Back</a></nav></header>
<form method="post" class="card">
  <label>Title<input name="title" value="<?= htmlspecialchars($title) ?>" required></label>
  <label>Body<textarea name="body" rows="6" required><?= htmlspecialchars($body) ?></textarea></label>
  <label><input type="checkbox" name="is_active" <?= $is_active ? 'checked' : '' ?>> Active</label>
  <button type="submit">Save</button>
</form>
</body>
</html>
<?php $conn->close(); ?>
