<?php
session_start();

require_once 'db_connector.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_project"])) {

    $project_id = $_POST['project_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Update project details in the database
    $stmt = $conn->prepare("UPDATE project SET title = ?, description = ?, start_date = ?, end_date = ? WHERE project_id = ?");
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $project_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect back to dashboard after updating the project
    header("Location: dashboard.php");
    exit();
}

// Fetch project details from the database
if (isset($_GET['project_id'])) {
    $project_id = $_GET['project_id'];

    $stmt = $conn->prepare("SELECT * FROM project WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if (!$project) {
        // Project not found
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect if project_id is not provided
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Project</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $project['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $project['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $project['start_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $project['end_date']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="update_project">Update Project</button>
        </form>
        <p><a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a></p>
    </div>
</body>
</html>
