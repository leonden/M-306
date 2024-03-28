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

    // Fetch firstname and lastname associated with user_id
    $stmt_name = $conn->prepare("SELECT firstname, lastname FROM user WHERE user_id = ?");
    $stmt_name->bind_param("i", $_SESSION['user_id']);
    $stmt_name->execute();
    $result_name = $stmt_name->get_result();
    $row_name = $result_name->fetch_assoc();
    $project_lead = $row_name['firstname'] . " " . $row_name['lastname']; // Concatenate firstname and lastname

    // Close statement
    $stmt_name->close();

    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Insert new project into the database
    $stmt = $conn->prepare("INSERT INTO project (title, description, start_date, end_date, project_lead) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $start_date, $end_date, $project_lead);
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Create New Project</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <input type="text" class="form-control" name="title" placeholder="Title" required>
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" placeholder="Description" required></textarea>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <button type="submit" class="btn btn-primary" name="create_project">Create Project</button>
        </form>
        <p><a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a></p>
    </div>
</body>
</html>