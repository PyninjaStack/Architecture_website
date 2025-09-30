<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: view/dashboard.php"); // fixed path to dashboard
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  <link rel="stylesheet" href="assets/css/admin.css"> <!-- Updated to correct CSS file -->
</head>
<body>
  <div class="login-container">
    <h2>Admin Login</h2>

    <?php if (isset($_GET['error'])): ?>
      <p class="error"><?= htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="actions/login.php" method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
