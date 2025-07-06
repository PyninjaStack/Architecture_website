<?php include '../components/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/projects.css">

<?php
// Get project code from URL
$code = $_GET['code'] ?? '';

// Load projects JSON
$projects = json_decode(file_get_contents('../data/projects.json'), true);

// Find the matching project
$project = null;
if ($code && $projects) {
  foreach ($projects as $p) {
    if (strtolower($p['code']) === strtolower($code)) {
      $project = $p;
      break;
    }
  }
}
?>

<main class="project-detail-layout">

  <!-- Vertical Sidebar Menu -->
  <nav class="project-menu-vertical">
    <ul class="project-menu-list">
      <li><a href="/Positive Space Design Studio/index.php" class="project-menu-link">PROJECTS</a></li>
      <li><a href="/Positive Space Design Studio/info.php" class="project-menu-link">INFO</a></li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="project-detail-page">
    <?php if ($project): ?>
      <h1><?= htmlspecialchars($project['title']) ?></h1>

      <!-- ✅ Slideshow if images exist -->
      <?php if (!empty($project['images'])): ?>
        <div class="slideshow-container">
          <?php foreach ($project['images'] as $index => $img): ?>
            <div class="slide <?= $index === 0 ? 'active' : '' ?>">
              <img src="../<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
            </div>
          <?php endforeach; ?>
          <button class="prev" onclick="changeSlide(-1)">❮</button>
          <button class="next" onclick="changeSlide(1)">❯</button>
        </div>
      <?php else: ?>
        <!-- ✅ Fallback to single thumbnail -->
        <div class="project-hero">
          <img src="../<?= htmlspecialchars($project['thumbnail']) ?>" alt="<?= htmlspecialchars($project['title']) ?>">
        </div>
      <?php endif; ?>

      <div class="project-info">
        <ul>
          <li><strong>Year:</strong> <?= htmlspecialchars($project['year']) ?></li>
          <li><strong>Category:</strong> <?= htmlspecialchars($project['category']) ?></li>
          <li><strong>Status:</strong> <?= htmlspecialchars($project['status']) ?></li>
          <li><strong>Scale:</strong> <?= htmlspecialchars($project['scale']) ?> sqm</li>
          <li><strong>Location:</strong> <?= htmlspecialchars($project['location']) ?></li>
        </ul>
      </div>

    <?php if (!empty($project['description'])): ?>
    <div class="project-description">
        <p><?= nl2br(htmlspecialchars($project['description'])) ?></p>
    </div>
    <?php endif; ?>


    <?php else: ?>
      <div class="not-found">
        <h2>Project Not Found</h2>
        <p>The project you're looking for doesn't exist or the code is invalid.</p>
        <a href="/Positive Space Design Studio/index.php" class="back-link">← Back to Projects</a>
      </div>
    <?php endif; ?>
  </div>

</main>

<!-- ✅ Slideshow script -->
<script>
let currentSlide = 0;
const slides = document.querySelectorAll(".slide");

function showSlide(index) {
  slides.forEach((s, i) => s.classList.toggle("active", i === index));
}

function changeSlide(n) {
  currentSlide = (currentSlide + n + slides.length) % slides.length;
  showSlide(currentSlide);
}
</script>

<?php include '../components/footer.php'; ?>
