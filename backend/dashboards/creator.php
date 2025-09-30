<?php
require_once "../auth.php";
checkRole(['content_creator']);
require_once "../db.php";
require_once "../includes/search_projects.php"; // ✅ reusable search

$userId = $_SESSION['user_id'];
$role   = $_SESSION['role'];
$search = strtolower(trim($_GET['search'] ?? ''));

// ✅ Fetch projects
$projects = searchProjects($conn, $search, $role, $userId);

// ✅ Helper: extract latest state from workflow history
function getLatestState($workflow) {
    if (!$workflow) return 'draft';
    $lines = preg_split('/\n→ /', $workflow);
    $last = trim(end($lines));
    $parts = explode(" by ", $last, 2);
    return strtolower(trim($parts[0]));
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Creator Dashboard</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
  <div class="dashboard-container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?> (Content Creator)</h2>

    <div class="dashboard-actions">
      <a href="../actions/add_project.php" class="btn-add">+ Add Project</a>
      <a href="../auth/logout.php" class="btn-logout">Logout</a>
    </div>

    <!-- ✅ Search Bar -->
    <form method="GET" action="" class="dashboard-search">
      <input type="text" name="search" placeholder="Search projects..."
             value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
      <button type="submit">Search</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>Code</th><th>Title</th><th>Year</th><th>Category</th>
          <th>Status</th><th>Scale</th><th>Location</th>
          <th>Lat</th><th>Long</th>
          <th>Thumbnail</th><th>Images</th>
          <th>Description</th><th>Selected</th>
          <th>Workflow</th><th>Review Notes</th>
          <th>Created At</th><th>Updated At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($projects) > 0): ?>
          <?php foreach ($projects as $p): ?>
            <?php $state = getLatestState($p['workflow_state']); ?>
            <tr>
              <td><?= htmlspecialchars($p['code']) ?></td>
              <td><?= htmlspecialchars($p['title']) ?></td>
              <td><?= htmlspecialchars($p['year']) ?></td>
              <td><?= htmlspecialchars($p['category']) ?></td>
              <td><?= htmlspecialchars($p['status']) ?></td>
              <td><?= htmlspecialchars($p['scale']) ?></td>
              <td><?= htmlspecialchars($p['location']) ?></td>
              <td><?= htmlspecialchars($p['latitude']) ?></td>
              <td><?= htmlspecialchars($p['longitude']) ?></td>

              <!-- Thumbnail -->
              <td>
                <?php if ($p['thumbnail']): ?>
                  <img src="<?= htmlspecialchars($p['thumbnail']) ?>" width="60">
                <?php else: ?>N/A<?php endif; ?>
              </td>

              <!-- Images Gallery -->
              <td>
                <?php 
                  $images = json_decode($p['images'] ?? '[]', true);
                  if ($images && is_array($images)) {
                    $count = count($images);
                    foreach (array_slice($images, 0, 3) as $img) {
                        echo '<img src="'.htmlspecialchars($img).'" width="40" style="margin:2px;">';
                    }
                    if ($count > 3) echo "<div>+ " . ($count-3) . " more</div>";
                  } else {
                    echo "0";
                  }
                ?>
              </td>

              <!-- Description -->
              <td><?= htmlspecialchars(substr($p['description'] ?? '', 0, 100)) ?>...</td>

              <!-- Selected -->
              <td><?= $p['selected'] ? 'Yes' : 'No' ?></td>

              <!-- Workflow -->
              <td><?= nl2br(htmlspecialchars($p['workflow_state'])) ?></td>

              <!-- Review Notes -->
              <td class="review-notes">
                <?= nl2br(htmlspecialchars($p['review_notes'])) ?>
              </td>

              <!-- Dates -->
              <td><?= htmlspecialchars($p['created_at']) ?></td>
              <td><?= htmlspecialchars($p['updated_at']) ?></td>

              <!-- Actions -->
              <td>
                <?php if (in_array($state, ['draft','changes_requested_by_editor','changes_requested_by_admin'])): ?>
                  <a href="../actions/edit_project.php?id=<?= $p['id'] ?>">Edit</a> |
                  <a href="../actions/update_state.php?id=<?= $p['id'] ?>&state=submitted_to_editor">Submit</a> |
                  <a href="../actions/delete_project.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete this project?')">Delete</a>
                <?php elseif ($state === 'approved'): ?>
                  <a href="../actions/update_state.php?id=<?= $p['id'] ?>&state=published">Publish</a>
                <?php else: ?>
                  <em>Waiting</em>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="18" style="text-align:center;">No projects found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
