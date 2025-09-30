<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: ../admin.php");
    exit;
}

function checkRole(array $allowed) {
    if (!in_array($_SESSION['role'], $allowed)) {
        header("HTTP/1.1 403 Forbidden");
        echo "<h1>403 Forbidden</h1>";
        exit;
    }
}
