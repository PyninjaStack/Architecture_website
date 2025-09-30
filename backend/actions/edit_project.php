<?php
require_once "../auth.php";
checkRole(['content_creator','admin_editor']);
require_once "../db.php";

$id = $_GET['id'] ?? null;
if (!$id) { die("Project ID missing"); }

// Role-based fetch
if ($_SESSION['role'] === 'content_creator') {
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = :id AND created_by = :uid");
    $stmt->execute(['id' => $id, 'uid' => $_SESSION['user_id']]);
} else {
    // Admin editor can fetch any project
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

$project = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$project) { die("Project not found or not yours."); }

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $year = $_POST['year'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $scale = $_POST['scale'];
    $location = $_POST['location'];
    $latitude = $_POST['latitude'] ?: null;
    $longitude = $_POST['longitude'] ?: null;
    $description = $_POST['description'];
    $selected = isset($_POST['selected']) ? 1 : 0;

    // thumbnail (optional update)
    $thumbnailPath = $project['thumbnail'];
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbName = time() . "_" . basename($_FILES['thumbnail']['name']);
        $target = "../uploads/" . $thumbName;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target);
        $thumbnailPath = "uploads/" . $thumbName;
    }

    // images (optional add new)
    $imagesArray = json_decode($project['images'] ?? "[]", true);
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            $imgName = time() . "_" . basename($_FILES['images']['name'][$i]);
            $target = "../uploads/" . $imgName;
            move_uploaded_file($tmp, $target);
            $imagesArray[] = "uploads/" . $imgName;
        }
    }
    $imagesJson = json_encode($imagesArray);

    // ---- Review Notes update ----
    $currentNotes = $project['review_notes'] ?? "";
    if ($_SESSION['role'] === 'admin_editor') {
        $currentNotes = "Edited by Admin Editor (" . $_SESSION['full_name'] . ") on " . date("Y-m-d H:i");
        /*$note = "Edited by Admin Editor (" . $_SESSION['full_name'] . ") on " . date("Y-m-d H:i");
        $currentNotes .= ($currentNotes ? "\n\n" : "") . $note;*/
    }

    $stmt = $conn->prepare("UPDATE projects 
        SET title=:title, year=:year, category=:category, status=:status, 
            scale=:scale, location=:location, latitude=:latitude, longitude=:longitude,
            thumbnail=:thumbnail, images=:images, description=:description, 
            selected=:selected, review_notes=:review_notes,
            last_edited_by=:uid, updated_at=NOW()
        WHERE id=:id");

    $stmt->execute([
        'title' => $title, 'year' => $year, 'category' => $category, 
        'status' => $status, 'scale' => $scale, 'location' => $location,
        'latitude' => $latitude, 'longitude' => $longitude,
        'thumbnail' => $thumbnailPath, 'images' => $imagesJson,
        'description' => $description, 'selected' => $selected,
        'review_notes' => $currentNotes,
        'uid' => $_SESSION['user_id'], 'id' => $id
    ]);

    if ($_SESSION['role'] === 'content_creator') {
        header("Location: ../dashboards/creator.php");
    } elseif ($_SESSION['role'] === 'admin_editor') {
        header("Location: ../dashboards/editor.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Project</title>
  <link rel="stylesheet" href="../assets/css/form.css">
  <script src="../assets/js/location.js"></script>
</head>
<body>
  <div class="form-container">
    <h2>Edit Project</h2>
    <form method="POST" enctype="multipart/form-data">

      <div class="form-row">
        <div class="form-group">
          <label>Code</label>
          <input type="text" value="<?= htmlspecialchars($project['code']) ?>" disabled>
        </div>
        <div class="form-group">
          <label>Title *</label>
          <input type="text" name="title" value="<?= htmlspecialchars($project['title']) ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Year *</label>
            <?php
                require_once "../partials/year_dropdown.php";
                renderYearDropdown("year", isset($project['year']) ? (int)$project['year'] : null);
            ?>
        </div>
        <div class="form-group">
          <label>Status *</label>
          <select name="status" required>
            <?php
              $statuses = ['idea','in progress','underconstruction','completed'];
              foreach ($statuses as $s) {
                $sel = ($project['status'] === $s) ? 'selected' : '';
                echo "<option value='$s' $sel>".ucfirst($s)."</option>";
              }
            ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Category *</label>
          <select name="category" required>
            <?php
              $categories = ['residential','commercial','public'];
              foreach ($categories as $c) {
                $sel = ($project['category'] === $c) ? 'selected' : '';
                echo "<option value='$c' $sel>".ucfirst($c)."</option>";
              }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label>Scale *</label>
          <input type="text" name="scale" value="<?= htmlspecialchars($project['scale']) ?>" required>
        </div>
      </div>

      <!-- Location dropdowns (API-based) -->
      <?php
        require_once "../partials/location_dropdown.php";
        renderLocationDropdown($project['location'] ?? "");
      ?>

      <div class="form-row">
        <div class="form-group">
          <label>Latitude</label>
          <input type="text" name="latitude" value="<?= htmlspecialchars($project['latitude']) ?>">
        </div>
        <div class="form-group">
          <label>Longitude</label>
          <input type="text" name="longitude" value="<?= htmlspecialchars($project['longitude']) ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Thumbnail</label>
          <input type="file" name="thumbnail" accept="image/*">
          <?php if ($project['thumbnail']): ?>
            <img src="../<?= $project['thumbnail'] ?>" width="80">
          <?php endif; ?>
        </div>
        <div class="form-group">
          <label>Images</label>
          <input type="file" name="images[]" accept="image/*" multiple>
        </div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"><?= htmlspecialchars($project['description']) ?></textarea>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="selected" <?= $project['selected'] ? 'checked' : '' ?>> Mark as Selected
        </label>
      </div>

      <button type="submit">Update Project</button>
    </form>
  </div>
</body>
</html>
