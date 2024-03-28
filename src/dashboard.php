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

// Function to retrieve all projects
function getAllProjects($conn) {
    $stmt = $conn->prepare("SELECT * FROM project");
    $stmt->execute();
    $result = $stmt->get_result();
    $projects = array();
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
    $stmt->close();
    return $projects;
}

// Delete project if requested
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_project"])) {
    $project_id = $_POST['project_id'];

    // Ensure the project belongs to the logged-in user before deleting
    $stmt = $conn->prepare("DELETE FROM project WHERE project_id = ? AND project_lead = ?");
    $stmt->bind_param("ii", $project_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    // Redirect back to dashboard after deleting the project
    header("Location: dashboard.php");
    exit();
}

$projects = getAllProjects($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <header style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Welcome to Your Dashboard</h2>
            <div class="container" style="width: fit-content; margin: 0; text-align: right;">
                <p style="margin: 0;">Hello, <?php echo $_SESSION['firstname']; ?>!</p>
                <div style="display: flex; font-size: 0.75rem">
                    <p style="margin: 0;"><a href="./account/edit_account.php">Edit Account</a></p>
                    <p style="margin-left: 0.5rem;"><a href="./account/logout.php">Logout</a></p>
                </div>
            </div>
        </header>
        <h3>Create New Project</h3>
        <p><a href="create_project.php" class="btn btn-primary">Create a new project</a></p>
        <h3>All Projects</h3>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Project Lead</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project) { ?>
                    <tr>
                        <td><?php echo $project['title']; ?></td>
                        <td><?php echo $project['description']; ?></td>
                        <td><?php echo $project['start_date']; ?></td>
                        <td><?php echo $project['end_date']; ?></td>
                        <td><?php echo $project['project_lead']; ?></td>
                        <td>
                        <?php 
                           if ($project['project_lead'] == $_SESSION['firstname'] . " " . $_SESSION['lastname']) {
                            echo "<a href=\"edit_project.php?project_id={$project['project_id']}\" class=\"btn btn-primary btn-sm\">Edit</a> ";
                            echo "<form method=\"post\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" style=\"display:inline;\">";
                            echo "<input type=\"hidden\" name=\"project_id\" value=\"{$project['project_id']}\">";
                            echo "<button type=\"submit\" class=\"btn btn-danger btn-sm\" name=\"delete_project\" onclick=\"return confirm('Are you sure you want to delete this project?');\">Delete</button>";
                            echo "</form>";
                        }
                        ?>
                    </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
