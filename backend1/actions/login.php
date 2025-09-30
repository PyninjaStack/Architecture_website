<?php
session_start();

// Replace with your credentials
$admin_user = "admin";
$admin_pass = "password123";

if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: ../view/dashboard.php");
} else {
    header("Location: ../admin.php?error=Invalid credentials");
}
exit;
