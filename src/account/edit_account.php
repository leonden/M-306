<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_password"])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Database connection
    $conn = new mysqli("localhost", "root", "", "taskmaster");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch user's current password from the database
    $stmt = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stored_password = $row['password'];
    $stmt->close();

    // Verify the current password
    if (password_verify($current_password, $stored_password)) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update password in the database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt_update = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
            $stmt_update->bind_param("si", $hashed_password, $_SESSION['user_id']);
            $stmt_update->execute();
            $stmt_update->close();
            $conn->close();

            // Password updated successfully
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "New password and confirm password do not match.";
        }
    } else {
        $error = "Incorrect current password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Edit Account</h2>
        <?php if($error) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="current_password">Current Password:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="update_password">Update Password</button>
        </form>
        <p><a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a></p>
    </div>
</body>
</html>
