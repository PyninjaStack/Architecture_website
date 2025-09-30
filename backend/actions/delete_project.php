<?php
require_once "../auth.php";
checkRole(['content_creator']);
require_once "../db.php";

$id = $_GET['id'] ?? null;
if (!$id) { die("Missing project ID"); }

$stmt = $conn->prepare("DELETE FROM projects WHERE id = :id AND created_by = :uid");
$stmt->execute(['id' => $id, 'uid' => $_SESSION['user_id']]);

header("Location: ../dashboards/creator.php");
exit;
