<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: admin.php'); exit; }
require_once __DIR__ . '/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = $conn->prepare("DELETE FROM notices WHERE id=?");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $stmt->close();
}
header('Location: add_notice.php');
exit;
