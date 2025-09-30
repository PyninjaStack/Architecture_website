<?php
require_once '../auth.php';
require_once '../db.php';

$code = $_GET['code'] ?? '';
if (!$code) {
    die("Project code is required.");
}

// Fetch project data
$stmt = $conn->prepare("SELECT * FROM projects WHERE code = :code");
$stmt->execute(['code' => $code]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    die("Project not found.");
}

$images = json_decode($project['images'] ?? '[]', true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Project - <?= htmlspecialchars($project['title']) ?></title>
  <link rel="stylesheet" href="../assets/css/edit.css">
</head>
<body>
  <div class="admin-container">
    <h1>Edit Project</h1>

    <form action="../actions/update_project.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="original_code" value="<?= htmlspecialchars($project['code']) ?>">

      <label>Project Code *</label>
      <input type="text" name="code" value="<?= htmlspecialchars($project['code']) ?>" required>

      <label>Title *</label>
      <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>

      <label>Year *</label>
      <input type="text" name="year" value="<?= htmlspecialchars($project['year']) ?>" required>

      <label>Category</label>
      <input type="text" name="category" value="<?= htmlspecialchars($project['category']) ?>">

      <label>Status</label>
      <input type="text" name="status" value="<?= htmlspecialchars($project['status']) ?>">

      <label>Scale (m²)</label>
      <input type="number" name="scale" value="<?= htmlspecialchars($project['scale']) ?>">

      <label>Location</label>
      <input type="text" name="location" value="<?= htmlspecialchars($project['location']) ?>">

      <label>Thumbnail (leave blank to keep existing)</label>
      <input type="file" name="thumbnail" accept="image/*">

      <label>Additional Images (Upload to Add)</label>
      <input type="file" name="images[]" multiple accept="image/*">

      <?php if (!empty($images)): ?>
        <div class="image-preview-section">
          <strong>Existing Images:</strong><br>
          <?php foreach ($images as $img): ?>
            <img src="/<?= htmlspecialchars($img) ?>" alt="" style="width:80px;height:auto;margin:5px;">
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <label>Description</label>
      <textarea name="description" rows="4"><?= htmlspecialchars($project['description']) ?></textarea>

      <label>
        <input type="checkbox" name="selected" <?= $project['selected'] ? 'checked' : '' ?>>
        Show in Selected Projects
      </label>

      <button type="submit">Update Project</button>
      <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    </form>
  </div>
</body>
</html>
