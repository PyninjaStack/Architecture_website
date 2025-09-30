<?php
require_once __DIR__ . '/../auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Project</title>
  <link rel="stylesheet" href="../assets/css/add.css">
</head>
<body>
  <div class="admin-container">
    <h1>Add New Project</h1>

    <?php if (isset($_GET['error'])): ?>
      <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php elseif (isset($_GET['success'])): ?>
      <p class="success">Project added successfully.</p>
    <?php endif; ?>

    <form action="../actions/add_project.php" method="POST" enctype="multipart/form-data">
      <label>Project Code *</label>
      <input type="text" name="code" required>

      <label>Title *</label>
      <input type="text" name="title" required>

      <label>Year *</label>
      <input type="text" name="year" required>

      <label>Category</label>
      <input type="text" name="category">

      <label>Status</label>
      <input type="text" name="status">

      <label>Scale (m²)</label>
      <input type="number" name="scale" step="1" min="0">

      <label>Location (city / country)</label>
      <input type="text" name="location">

      <label>Latitude</label>
      <input type="number" name="latitude" step="0.000001" min="-90" max="90" placeholder="">

      <label>Longitude</label>
      <input type="number" name="longitude" step="0.000001" min="-180" max="180" placeholder="">

      <label>Thumbnail Image (file upload)</label>
      <input type="file" name="thumbnail" accept="image/*">

      <label>Additional Images (multiple)</label>
      <input type="file" name="images[]" multiple accept="image/*">

      <label>Description</label>
      <textarea name="description" rows="4"></textarea>

      <label>
        <input type="checkbox" name="selected"> Show in Selected Projects
      </label>

      <button type="submit">Add Project</button>
      <a href="dashboard.php" class="btn-back">← Back to Dashboard</a>
    </form>
  </div>
</body>
</html>
