<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../auth.php";
checkRole(['content_creator', 'admin_editor', 'administrator']);
require_once "../db.php";

$id    = $_GET['id'] ?? null;
$state = $_GET['state'] ?? null;

if (!$id || !$state) { die("Missing parameters"); }

// ✅ Fetch current project
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = :id");
$stmt->execute(['id' => $id]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) { die("Project not found"); }

// ✅ Always initialize review notes
$currentNotes = $project['review_notes'] ?? "";

// ✅ If state is "request changes" → require a note
if (strpos($state, 'changes_requested') !== false) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $noteText = trim($_POST['review_note']);
        if ($noteText) {
            $newNote = "Request Changes by " . ucfirst($_SESSION['role']) .
                       " (" . $_SESSION['full_name'] . ") on " . date("Y-m-d H:i") .
                       "\n" . $noteText;
            // Append for request changes
            $currentNotes .= ($currentNotes ? "\n\n" : "") . $newNote;
        }
    } else {
        // Show simple form if note not provided yet
        ?>
        <form method="POST">
          <textarea name="review_note" placeholder="Enter review notes..." required></textarea>
          <br>
          <button type="submit">Submit Review Note</button>
        </form>
        <?php
        exit;
    }
} else {
    // ✅ For Submit transitions → only update workflow, keep notes same
    $currentNotes = $project['review_notes'] ?? "";
}

// ✅ Append workflow history
$newWorkflowEntry = ucfirst($state) . " by " . ucfirst($_SESSION['role']) .
                    " (" . $_SESSION['full_name'] . ") on " . date("Y-m-d H:i");

$updatedWorkflow = ($project['workflow_state'] ?? "");
$updatedWorkflow .= ($updatedWorkflow ? "\n→ " : "") . $newWorkflowEntry;

$stmt = $conn->prepare("UPDATE projects 
    SET workflow_state = :workflow,
        last_edited_by = :uid,
        updated_at = NOW(),
        review_notes = :notes
    WHERE id = :id");

$stmt->execute([
    'workflow' => $updatedWorkflow,
    'uid'      => $_SESSION['user_id'],
    'id'       => $id,
    'notes'    => $currentNotes
]);


// ✅ Redirect based on role
if ($_SESSION['role'] === 'content_creator') {
    header("Location: ../dashboards/creator.php");
} elseif ($_SESSION['role'] === 'admin_editor') {
    header("Location: ../dashboards/editor.php");
} else {
    header("Location: ../dashboards/admin.php");
}
exit;
