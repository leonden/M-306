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
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Welcome to Your Dashboard</h2>
    <h3>Create New Project</h3>
    <p><a href="create_project.php"><button>Create a new project</button></a></p>
    <h3>All Projects</h3>
    <table>
        <thead>
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
                    if ($project['project_lead'] == $_SESSION['user_id']) {
                        echo "<a href=\"edit_project.php?project_id={$project['project_id']}\">Edit</a> | ";
                        echo "<form method=\"post\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" style=\"display:inline;\">";
                        echo "<input type=\"hidden\" name=\"project_id\" value=\"{$project['project_id']}\">";
                        echo "<input type=\"submit\" name=\"delete_project\" value=\"Delete\" onclick=\"return confirm('Are you sure you want to delete this project?');\">";
                        echo "</form>";
                    }
                    ?>
                </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="./account/logout.php">Logout</a>
</body>
</html>


