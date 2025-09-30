<?php
require_once "../auth.php";
checkRole(['content_creator']);
require_once "../db.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $code = trim($_POST['code']);
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

    // handle file uploads
    $thumbnailPath = null;
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbName = time() . "_" . basename($_FILES['thumbnail']['name']);
        $target = "../uploads/" . $thumbName;
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target);
        $thumbnailPath = "uploads/" . $thumbName;
    }

    $imagesArray = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            $imgName = time() . "_" . basename($_FILES['images']['name'][$i]);
            $target = "../uploads/" . $imgName;
            move_uploaded_file($tmp, $target);
            $imagesArray[] = "uploads/" . $imgName;
        }
    }
    $imagesJson = json_encode($imagesArray);

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO projects 
        (code, title, year, category, status, scale, location, latitude, longitude, thumbnail, images, description, selected, created_by) 
        VALUES (:code, :title, :year, :category, :status, :scale, :location, :latitude, :longitude, :thumbnail, :images, :description, :selected, :uid)");

    $stmt->execute([
        'code' => $code,
        'title' => $title,
        'year' => $year,
        'category' => $category,
        'status' => $status,
        'scale' => $scale,
        'location' => $location,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'thumbnail' => $thumbnailPath,
        'images' => $imagesJson,
        'description' => $description,
        'selected' => $selected,
        'uid' => $_SESSION['user_id']
    ]);

    header("Location: ../dashboards/creator.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Project</title>
  <link rel="stylesheet" href="../assets/css/form.css">
  <script>
    // Generate year dropdown from 2015 to 2115
    /*window.onload = function() {
      const yearSelect = document.getElementById("year");
      for (let y = 2015; y <= 2115; y++) {
        let opt = document.createElement("option");
        opt.value = y;
        opt.innerHTML = y;
        yearSelect.appendChild(opt);
      }
      loadCountries();
    }*/

    // Fetch countries
    async function loadCountries() {
      const res = await fetch("https://countriesnow.space/api/v0.1/countries/positions");
      const data = await res.json();
      const countrySelect = document.getElementById("country");
      data.data.forEach(c => {
        let opt = document.createElement("option");
        opt.value = c.name;
        opt.innerHTML = c.name;
        countrySelect.appendChild(opt);
      });
    }

    // Fetch states based on country
    async function updateStates() {
      const country = document.getElementById("country").value;
      const stateSelect = document.getElementById("state");
      stateSelect.innerHTML = "<option value=''>Select State</option>";
      document.getElementById("city").innerHTML = "<option value=''>Select City</option>";

      if (!country) return;

      const res = await fetch("https://countriesnow.space/api/v0.1/countries/states", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ country })
      });
      const data = await res.json();
      if (data.data.states) {
        data.data.states.forEach(s => {
          let opt = document.createElement("option");
          opt.value = s.name;
          opt.innerHTML = s.name;
          stateSelect.appendChild(opt);
        });
      }
      finalizeLocation();
    }

    // Fetch cities based on state
    async function updateCities() {
      const country = document.getElementById("country").value;
      const state = document.getElementById("state").value;
      const citySelect = document.getElementById("city");
      citySelect.innerHTML = "<option value=''>Select City</option>";

      if (!country || !state) return;

      const res = await fetch("https://countriesnow.space/api/v0.1/countries/state/cities", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ country, state })
      });
      const data = await res.json();
      if (data.data) {
        data.data.forEach(city => {
          let opt = document.createElement("option");
          opt.value = city;
          opt.innerHTML = city;
          citySelect.appendChild(opt);
        });
      }
      finalizeLocation();
    }

    // Update hidden location field
    function finalizeLocation() {
      const country = document.getElementById("country").value;
      const state = document.getElementById("state").value;
      const city = document.getElementById("city").value;
      document.getElementById("location").value = [country, state, city].filter(Boolean).join(", ");
    }
  </script>
</head>
<body>
  <div class="form-container">
    <h2>Add Project</h2>
    <form method="POST" enctype="multipart/form-data">

      <div class="form-row">
        <div class="form-group">
          <label>Code *</label>
          <input type="text" name="code" required>
        </div>
        <div class="form-group">
          <label>Title *</label>
          <input type="text" name="title" required>
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
            <option value="idea">Idea</option>
            <option value="in progress">In Progress</option>
            <option value="underconstruction">Under Construction</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Category *</label>
          <select name="category" required>
            <option value="residential">Residential</option>
            <option value="commercial">Commercial Housing</option>
            <option value="residential">Commercial</option>
            <option value="public">Educational</option>
            <option value="residential">Religious</option>
            <option value="public">Hotel</option>
            <option value="commercial">Industrial</option>
            <option value="public">Warehouse</option>
            <option value="public">Urban Development</option>
            <option value="public">Interior</option>
            <option value="public">Landscape</option>
            <!-- Add more as needed -->
          </select>
        </div>
        <div class="form-group">
          <label>Scale/Built Up Area (Unit in Square Meter) *</label>
          <input type="text" name="scale" placeholder="Enter scale" required>
        </div>
      </div>

      <!-- Location cascading -->
      <?php
        require_once "../partials/location_dropdown.php";
        renderLocationDropdown($project['location'] ?? "");
      ?>
      <div class="form-row">
        <div class="form-group">
          <label>Latitude</label>
          <input type="text" name="latitude">
        </div>
        <div class="form-group">
          <label>Longitude</label>
          <input type="text" name="longitude">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Thumbnail</label>
          <input type="file" name="thumbnail" accept="image/*">
        </div>
        <div class="form-group">
          <label>Images</label>
          <input type="file" name="images[]" accept="image/*" multiple>
        </div>
      </div>

      <div class="form-group">
        <label>Description</label>
        <textarea name="description" rows="3"></textarea>
      </div>

      <div class="form-group">
        <label>
          <input type="checkbox" name="selected"> Mark as Selected Project
        </label>
      </div>

      <button type="submit">Save Project</button>
    </form>
  </div>
</body>
</html>
