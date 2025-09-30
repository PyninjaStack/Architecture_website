<?php
$host = "localhost";
$dbname = "positive_space_studio";
$username = "root"; // default in XAMPP
$password = "";     // leave empty unless set

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
