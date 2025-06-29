<!-- components/header.php -->
<?php
$currentPath = $_SERVER['REQUEST_URI'];
$isInfoPage = str_contains($currentPath, '/pages/info-section/') !== false;
?>

<nav class="navbar">
  <a href="/Positive Space Design Studio/index.php" class="logo">Positive Space Design Studio</a>

  <ul class="menu">
    <?php if ($isInfoPage): ?>
      <li><a href="/Positive Space Design Studio/index.php">PROJECTS</a></li>
    <?php else: ?>
      <li><a href="/Positive Space Design Studio/info.php">INFO</a></li>
    <?php endif; ?>
  </ul>
</nav>


<!-- OLD VERSION -->
<!--<header>
  <nav class="navbar">
    <a href="/Positive Space Design Studio/index.php" class="logo">Positive Space Design Studio</a>
    <ul class="menu">
      <li><a href="/Positive Space Design Studio/index.php">Projects</a></li>
      <li><a href="/Positive Space Design Studio/info.php">Info</a></li>
    </ul>
  </nav>
</header> -->
