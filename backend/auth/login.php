<?php
session_start();
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND active = 1");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name']; 
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'content_creator') {
            header("Location: ../dashboards/creator.php");
        } elseif ($user['role'] === 'admin_editor') {
            header("Location: ../dashboards/editor.php");
        } else {
            header("Location: ../dashboards/administrator.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "Invalid login credentials!";
        header("Location: ../admin.php");
        exit;
    }
}
