<?php
$host = 'localhost';         // or your host
$dbname = 'positive_space_studio';    // replace with your actual DB name
$username = 'root'; // your DB username
$password = ''; // your DB password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
