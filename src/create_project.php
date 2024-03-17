<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_project"])) {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "taskmaster");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    $project_lead = $_SESSION['user_id'];

    // Insert new project into the database
    $stmt = $conn->prepare("INSERT INTO project (title, description, start_date, end_date, project_lead) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $project_lead);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // Redirect back to dashboard after creating the project
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Project</title>
</head>
<body>
    <h2>Create New Project</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="title" placeholder="Title" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        Start Date: <input type="date" name="start_date" required><br>
        End Date: <input type="date" name="end_date" required><br>
        <input type="submit" name="create_project" value="Create Project">
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
