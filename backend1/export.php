<?php
require 'config/db.php';
$stmt = $conn->query("SELECT * FROM projects");
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
header('Content-Type: application/json');
echo json_encode($projects, JSON_PRETTY_PRINT);
