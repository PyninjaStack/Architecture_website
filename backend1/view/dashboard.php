<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit;
}

require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

/*$search = $_GET['search'] ?? '';
$stmt = $conn->prepare("SELECT * FROM projects WHERE title LIKE :search ORDER BY year DESC");
$stmt->execute(['search' => "%$search%"]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);*/

$search = strtolower($_GET['search'] ?? '');
$stmt = $conn->prepare("
    SELECT * FROM projects 
    WHERE 
        LOWER(title) LIKE :search 
        OR LOWER(code) LIKE :search
        OR LOWER(location) LIKE :search
        OR LOWER(category) LIKE :search
        OR LOWER(status) LIKE :search
        OR LOWER(latitude) LIKE :search
        OR LOWER(longitude) LIKE :search
        OR LOWER(description) LIKE :search
    ORDER BY year DESC
");
$stmt->execute(['search' => "%$search%"]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
  <div class="dashboard-container">
    <h2>Project Dashboard</h2>

    <div class="dashboard-actions">
        <a href="add.php" class="btn-add">+ Add Project</a>
        <a href="../actions/logout.php" class="btn-logout">Logout</a>
    </div>

    <form method="GET" action="">
      <input type="text" name="search" placeholder="Search projects..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Code</th>
                <th>Title</th>
                <th>Year</th>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Status</th>
                <th>Category</th>
                <th>Scale</th>
                <th>Thumbnail</th>
                <th>Images</th>
                <th>Description</th>
                <th>Selected</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl = 1;
            foreach ($projects as $p): ?>
            <tr>
                <td><?= $sl++ ?></td>
                <td><?= htmlspecialchars($p['code']) ?></td>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['year']) ?></td>
                <td><?= htmlspecialchars($p['location']) ?></td>
                <td><?= htmlspecialchars($p['latitude'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['longitude'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['status'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['category'] ?? '-') ?></td>
                <td><?= htmlspecialchars($p['scale'] ?? '-') ?></td>

                <!-- Thumbnail Preview -->
                <td>
                    <?php if (!empty($p['thumbnail'])): ?>
                        <img src="<?= htmlspecialchars($p['thumbnail']) ?>" alt="Thumbnail" width="50">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>

                <!-- Images Count -->
                <td>
                    <?php
                    $images = json_decode($p['images'] ?? '[]', true);
                    echo is_array($images) ? count($images) . ' image(s)' : '0';
                    ?>
                </td>

                <!-- Description Snippet -->
                <td>
                    <?= isset($p['description']) ? htmlspecialchars(substr($p['description'], 0, 50)) . '...' : '-' ?>
                </td>

                <!-- Selected -->
                <td><?= isset($p['selected']) && $p['selected'] ? 'Yes' : 'No' ?></td>

                <!-- Actions -->
                <td>
                    <a href="edit.php?code=<?= urlencode($p['code']) ?>">Edit</a> |
                    <a href="../actions/delete_project.php?code=<?= urlencode($p['code']) ?>" onclick="return confirm('Delete this project?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
  </div>
</body>
</html>
