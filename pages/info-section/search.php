<?php include '../../components/header.php'; ?>
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/info.css">

<main class="info-section">
  <?php include '../../components/info-menu.php'; ?>

  <div class="info-content">
    <h1>SEARCH PROJECTS</h1>

    <form method="GET" action="search.php" class="search-bar">
      <input type="text" name="query" placeholder="Search by project name,etc" value="<?= htmlspecialchars($_GET['query'] ?? '') ?>" required>
      <button type="submit">Search</button>
    </form>

    <?php
    $query = strtolower(trim($_GET['query'] ?? ''));
    $projects = json_decode(file_get_contents('../../data/projects.json'), true);

    $results = [];
    if ($query && $projects) {
        foreach ($projects as $project) {
            // Match against multiple fields
            if (
                strpos(strtolower($project['title']), $query) !== false ||
                strpos(strtolower($project['location']), $query) !== false ||
                strpos(strtolower($project['status']), $query) !== false ||
                strpos(strtolower($project['category']), $query) !== false
            ) {
                $results[] = $project;
            }
        }
    }

    if ($query):
        if (count($results) > 0): ?>
          <div class="search-results">
            <h2><?= count($results) ?> result(s) found:</h2>
            <div class="project-grid">
              <?php foreach ($results as $project): ?>
                <a href="/Positive Space Design Studio/pages/projects.php?code=<?= $project['code'] ?>" class="project-thumb" 
                   data-year="<?= $project['year'] ?>" 
                   data-title="<?= $project['title'] ?>" 
                   data-program="<?= $project['category'] ?>" 
                   data-scale="<?= $project['scale'] ?>" 
                   data-status="<?= $project['status'] ?>" 
                   data-location="<?= $project['location'] ?>">
                  <div class="project-block">
                    <img src="../../<?= $project['thumbnail'] ?>" alt="<?= $project['title'] ?>">
                    <p><?= $project['title'] ?></p>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
          <p>No matching projects found for "<strong><?= htmlspecialchars($query) ?></strong>".</p>
        <?php endif;
    endif;
    ?>
  </div>
</main>

<?php include '../../components/footer.php'; ?>
