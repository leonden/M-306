<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if new password and confirm password match
    if ($new_password !== $confirm_password) {
        $message = "New password and confirm password do not match.";
    } else {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "taskmaster");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Fetch current user's password from database
        $stmt = $conn->prepare("SELECT password FROM user WHERE username = ?");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify if current password matches the stored password
        if (password_verify($current_password, $stored_password)) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update user's password in the database
            $update_stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $_SESSION['username']);
            $update_stmt->execute();
            $update_stmt->close();

            $message = "Password changed successfully.";
        } else {
            $message = "Incorrect current password.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<body>
    <h2>Change Password</h2>
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" required><br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required><br>
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" required><br>
        <input type="submit" name="change_password" value="Change Password">
    </form>
    <p><a href="../dashboard.php">Back to Dashboard</a></p>
</body>
</html>
