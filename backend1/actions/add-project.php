<?php
// backend/actions/add_project.php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';

function safeFilename($name) {
    // basic sanitize and unique suffix
    $name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $name);
    $name = substr($name, 0, 200);
    return $name;
}

try {
    // required fields
    $code = trim($_POST['code'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $year = trim($_POST['year'] ?? '');
    if (!$code || !$title || !$year) {
        throw new Exception('Please fill required fields (code, title, year).');
    }

    // optional fields
    $category = trim($_POST['category'] ?? null);
    $status = trim($_POST['status'] ?? null);
    $scale = trim($_POST['scale'] ?? null);
    $location = trim($_POST['location'] ?? null);
    $description = trim($_POST['description'] ?? null);
    $selected = isset($_POST['selected']) ? 1 : 0;

    // latitude / longitude (optional) - validate as floats or NULL
    $latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT) : null;
    $longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT) : null;

    if ($latitude !== null && ($latitude < -90 || $latitude > 90)) {
        throw new Exception('Latitude must be between -90 and 90.');
    }
    if ($longitude !== null && ($longitude < -180 || $longitude > 180)) {
        throw new Exception('Longitude must be between -180 and 180.');
    }

    // handle uploads -> store in root assets/images/
    $targetDir = realpath(__DIR__ . '/../../assets/images');
    if (!$targetDir) {
        // if folder doesn't exist, attempt to create it
        $targetDir = __DIR__ . '/../../assets/images';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $targetDir = realpath($targetDir);
    }

    // Thumbnail
    $thumbnailPath = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $orig = $_FILES['thumbnail']['name'];
        $safe = time() . '_' . safeFilename($orig);
        $dst = $targetDir . DIRECTORY_SEPARATOR . $safe;
        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dst)) {
            throw new Exception('Failed to move thumbnail upload.');
        }
        // store relative path used by frontend (adjust according to your site structure)
        $thumbnailPath = 'assets/images/' . $safe;
    }

    // Multiple additional images
    $imagesArray = [];
    if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            if (!$_FILES['images']['name'][$i]) continue;
            $orig = $_FILES['images']['name'][$i];
            $tmp  = $_FILES['images']['tmp_name'][$i];
            $safe = time() . '_' . $i . '_' . safeFilename($orig);
            $dst = $targetDir . DIRECTORY_SEPARATOR . $safe;
            if (!move_uploaded_file($tmp, $dst)) {
                // skip or throw; we'll skip silently
                continue;
            }
            $imagesArray[] = 'assets/images/' . $safe;
        }
    }

    // If no thumbnail given but images present, use first image as thumbnail
    if (!$thumbnailPath && count($imagesArray) > 0) {
        $thumbnailPath = $imagesArray[0];
    }

    // JSON-encode images array for DB (or NULL)
    $imagesJson = count($imagesArray) ? json_encode($imagesArray, JSON_UNESCAPED_SLASHES) : null;

    // Insert into DB
    $sql = "INSERT INTO projects
      (code, title, year, category, status, scale, location, latitude, longitude, thumbnail, images, description, selected)
      VALUES
      (:code, :title, :year, :category, :status, :scale, :location, :latitude, :longitude, :thumbnail, :images, :description, :selected)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':code' => $code,
        ':title' => $title,
        ':year' => $year,
        ':category' => $category,
        ':status' => $status,
        ':scale' => $scale,
        ':location' => $location,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':thumbnail' => $thumbnailPath,
        ':images' => $imagesJson,
        ':description' => $description,
        ':selected' => $selected
    ]);

    header('Location: ../view/add.php?success=1');
    exit;
} catch (Exception $e) {
    $msg = urlencode($e->getMessage());
    header("Location: ../view/add.php?error={$msg}");
    exit;
}
