<?php include '../components/header.php'; ?>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/projects.css">
<script src="../assets/js/main.js" defer></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>



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

    <div class="project-footer">
      <!-- Social Media Section -->
      <div class="social-icons project-social">
        <!-- Instagram -->
        <div class="social-icon-wrapper">
          <a href="https://instagram.com/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/instagram.jpg" alt="Instagram" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://instagram.com/yourprofile" />
        </div>

        <!-- Facebook -->
        <div class="social-icon-wrapper">
          <a href="https://facebook.com/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/facebook.jpg" alt="Facebook" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://facebook.com/yourprofile" />
        </div>

        <!-- LinkedIn -->
        <div class="social-icon-wrapper">
          <a href="https://linkedin.com/in/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/linkedin.jpg" alt="LinkedIn" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://linkedin.com/in/yourprofile" />
        </div>

        <!-- Twitter -->
        <div class="social-icon-wrapper">
          <a href="https://twitter.com/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/twitter.jpg" alt="Twitter" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://twitter.com/yourprofile" />
        </div>

        <!-- Gmail -->
        <div class="social-icon-wrapper">
          <a href="mailto:your@email.com">
            <img src="/Positive Space Design Studio/assets/icons/gmail.jpg" alt="Gmail" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="mailto:your@email.com" />
        </div>

        <!-- YouTube -->
        <div class="social-icon-wrapper">
          <a href="https://youtube.com/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/youtube.jpg" alt="YouTube" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://youtube.com/yourprofile" />
        </div>

        <!-- Pinterest -->
        <div class="social-icon-wrapper">
          <a href="https://pinterest.com/yourprofile" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/pinterest.jpg" alt="Pinterest" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://pinterest.com/yourprofile" />
        </div>

        <!-- WhatsApp -->
        <div class="social-icon-wrapper">
          <a href="https://wa.me/911234567890" target="_blank">
            <img src="/Positive Space Design Studio/assets/icons/whatsapp.jpg" alt="WhatsApp" />
          </a>
          <img src="/Positive Space Design Studio/assets/icons/share.jpg" 
              class="share-icon" 
              alt="Share" 
              data-share-url="https://wa.me/911234567890" />
        </div>
        </div>
        <div class="project-actions">
          <!-- <button class="like-btn" data-id="<?= $project['id'] ?>">👍 Like (<span id="like-count-<?= $project['id'] ?>"><?= $project['likes'] ?></span>)</button>
          <button class="dislike-btn" data-id="<?= $project['id'] ?>">👎 Dislike (<span id="dislike-count-<?= $project['id'] ?>"><?= $project['dislikes'] ?></span>)</button> -->
          <button class="share-btn" data-url="https://yourdomain.com/projects.php?code=<?= urlencode($project['code']) ?>">📤 Share</button>
        </div>
      </div>
    

      


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

    <!-- ✅ Project Location Map Section -->
    <?php if (!empty($project['latitude']) && !empty($project['longitude'])): ?>
      <section id="map-section" class="project-location-map">
        <h2>Project Location</h2>
        <div id="project-map" style="height: 400px; width: 100%; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); margin-top: 1rem;"></div>
      </section>
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

<?php if (!empty($project['latitude']) && !empty($project['longitude'])): ?>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const lat = <?= json_encode($project['latitude']) ?>;
    const lng = <?= json_encode($project['longitude']) ?>;

    const map = L.map('project-map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng]).addTo(map);

    // 🟢 When clicking the marker or map, open Google Maps
    marker.on('click', () => {
      window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
    });

    map.on('click', () => {
      window.open(`https://www.google.com/maps?q=${lat},${lng}`, '_blank');
    });
  });
</script>
<?php endif; ?>



<?php include '../components/footer.php'; ?>
