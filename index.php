<!-- index.php -->
<?php include 'components/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js" defer></script>



<main class="container">

  <!-- 🔽 Project List Area -->
  <section id="project-list">
    <!-- Project cards will be dynamically rendered here -->
  </section>



  <div class="filters-bar">
  <div class="filters-left">
    <a href="info.php" class="filter-button">INFO</a>
  </div>

  <div class="filters-center">
    <button data-filter="chronological" class="filter-button active">CHRONOLOGICAL</button>
    <button data-filter="alphabetical" class="filter-button">ALPHABETICAL</button>
    <button data-filter="programmatic" class="filter-button">PROGRAMMATIC</button>
    <button data-filter="scale" class="filter-button">SCALE</button>
    <button data-filter="status" class="filter-button">STATUS</button>
    <button data-filter="location" class="filter-button">LOCATION</button>
  </div>

  <div class="filters-right">
    <button id="toggle-projects" class="filter-button">ALL PROJECTS</button>
  </div>
</div>
</main>
<?php include 'components/footer.php'; ?>




<!-- index.php -->
<?//php include 'components/header.php'; ?>
<!--<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js" defer></script>

<main class="container">-->

  <!-- 🔽 Project List Area -->
  <!--<section id="project-list">-->
    <!-- Project cards will be dynamically rendered here -->
  <!--</section>

  <div class="filters-bar">
    <div class="filters-left">
      <a href="info.php" class="filter-button" id="info-link">INFO</a>
    </div>

    <div class="filters-center">
      <button data-filter="chronological" class="filter-button active">CHRONOLOGICAL</button>
      <button data-filter="alphabetical" class="filter-button">ALPHABETICAL</button>
      <button data-filter="programmatic" class="filter-button">PROGRAMMATIC</button>
      <button data-filter="scale" class="filter-button">SCALE</button>
      <button data-filter="status" class="filter-button">STATUS</button>
      <button data-filter="location" class="filter-button">LOCATION</button>
    </div>

    <div class="filters-right">
      <button id="toggle-projects" class="filter-button">ALL PROJECTS</button>
    </div>
  </div>

</main>-->

<!-- 🔽 Sliding Info Page Container -->
<!--<div id="info-slide-container" class="info-slide-hidden"></div>-->

<?//php include 'components/footer.php'; ?>

<!-- 🔽 Page Slide Script -->
<!--<script>
  const infoLink = document.getElementById('info-link');
  const infoContainer = document.getElementById('info-slide-container');

  infoLink.addEventListener('click', function(e) {
    e.preventDefault();

    // Fetch and show info.php
    fetch('info.php')
      .then(res => res.text())
      .then(html => {
        infoContainer.innerHTML = html;
        infoContainer.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
  });

  // Listen for close button inside dynamically loaded content
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('close-info-slide')) {
      infoContainer.classList.remove('active');
      document.body.style.overflow = '';
      infoContainer.innerHTML = '';
    }
  });
</script>-->

