<!-- index.php -->
<?php include 'components/header.php'; ?>
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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
  <!-- World map (shown when Location filter is active) -->
  <div id="map-plotly"></div>
</main>
<?php include 'components/footer.php'; ?>


