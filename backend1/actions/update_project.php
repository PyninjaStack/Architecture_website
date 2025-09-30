<?php
require_once '../auth.php';
require_once '../db.php';

$originalCode = $_POST['original_code'];
$code = $_POST['code'];
$title = $_POST['title'];
$year = $_POST['year'];
$category = $_POST['category'] ?? '';
$status = $_POST['status'] ?? '';
$scale = $_POST['scale'] ?? '';
$location = $_POST['location'] ?? '';
$description = $_POST['description'] ?? '';
$selected = isset($_POST['selected']) ? 1 : 0;

$thumbnailPath = '';
$imagePaths = [];

// Handle thumbnail update
if (!empty($_FILES['thumbnail']['name'])) {
    $thumbName = uniqid() . '_' . $_FILES['thumbnail']['name'];
    $thumbPath = 'uploads/' . $thumbName;
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../../assets/images/' . $thumbName);
    $thumbnailPath = 'assets/images/' . $thumbName;
}

// Get current images if exist
$stmt = $conn->prepare("SELECT thumbnail, images FROM projects WHERE code = :code");
$stmt->execute(['code' => $originalCode]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

$existingImages = json_decode($existing['images'] ?? '[]', true);
$existingThumbnail = $existing['thumbnail'] ?? '';

// Handle new additional images
if (!empty($_FILES['images']['name'][0])) {
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        $filename = uniqid() . '_' . $_FILES['images']['name'][$key];
        move_uploaded_file($tmpName, '../../assets/images/' . $filename);
        $imagePaths[] = 'assets/images/' . $filename;
    }
}

// Merge with existing images
$finalImages = array_merge($existingImages, $imagePaths);
$finalThumbnail = $thumbnailPath ?: $existingThumbnail;

// Update project
$stmt = $conn->prepare("UPDATE projects SET 
    code = :code, title = :title, year = :year, category = :category, 
    status = :status, scale = :scale, location = :location,
    thumbnail = :thumbnail, images = :images, description = :description,
    selected = :selected
    WHERE code = :originalCode
");

$stmt->execute([
    'code' => $code,
    'title' => $title,
    'year' => $year,
    'category' => $category,
    'status' => $status,
    'scale' => $scale,
    'location' => $location,
    'thumbnail' => $finalThumbnail,
    'images' => json_encode($finalImages),
    'description' => $description,
    'selected' => $selected,
    'originalCode' => $originalCode
]);

header("Location: ../view/dashboard.php");
exit;
