<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "taskmaster");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    // Fetch project details
    $stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ? AND project_lead = ?");
    $stmt->bind_param("ii", $project_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $project = $result->fetch_assoc();
    } else {
        echo "Project not found or you don't have permission to edit it.";
        exit();
    }
    $stmt->close();
} else {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_project"])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Update project
    $stmt = $conn->prepare("UPDATE project SET title=?, description=?, start_date=?, end_date=? WHERE project_id=?");
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $project_id);
    if ($stmt->execute()) {
        // Redirect to dashboard after editing
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error updating project: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
</head>
<body>
    <h2>Edit Project</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="title" value="<?php echo $project['title']; ?>" required><br>
        <textarea name="description" required><?php echo $project['description']; ?></textarea><br>
        Start Date: <input type="date" name="start_date" value="<?php echo $project['start_date']; ?>" required><br>
        End Date: <input type="date" name="end_date" value="<?php echo $project['end_date']; ?>" required><br>
        <input type="submit" name="edit_project" value="Save Changes">
        <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
