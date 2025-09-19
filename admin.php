<?php
session_start();
require_once __DIR__ . '/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $stmt = $conn->prepare("SELECT id, password_hash FROM admins WHERE email = ?");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $stmt->bind_result($aid, $hash);
  if ($stmt->fetch() && password_verify($password, $hash)) {
    $_SESSION['admin_id'] = $aid;
    header('Location: add_notice.php');
    exit;
  } else {
    $err = 'Invalid credentials';
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="centered">
  <form class="card" method="post" autocomplete="off">
    <h1>Admin Login</h1>
    <?php if ($err): ?><p class="error"><?= htmlspecialchars($err) ?></p><?php endif; ?>
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button type="submit">Login</button>
  </form>
</body>
</html>
