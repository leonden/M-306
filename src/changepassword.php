<?php
session_start();
include('dbconnector.php');
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    // Session not OK, redirect to login page
    header("Location: index.php");
    die();
}

// Get the ID of the logged-in user
$user_id = $_SESSION['userID'];

// Check if the user ID is valid
$stmt = $mysqli->prepare("SELECT * FROM benutzer WHERE ID = ?");
if (!$stmt) {
    die("Error: " . $mysqli->error);
}
$stmt->bind_param("i", $user_id);
if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // User ID is not valid, redirect to login page
    header("Location: index.php");
    die();
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get form data
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Check if the old password is correct
    $stmt = $mysqli->prepare("SELECT password FROM benutzer WHERE ID = ?");
    if (!$stmt) {
        die("Error: " . $mysqli->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Error: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        // Verify the old password
        if (password_verify($old_password, $stored_password)) {
            // Old password is correct, proceed to update the password
            // Check if the password meets the requirements
            if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[a-z]/', $new_password) || !preg_match('/[0-9]/', $new_password)) {
                $error = "Das Passwort erfüllt nicht die Anforderungen. (Mindestens 8 Zeichen, ein Großbuchstabe, ein Kleinbuchstabe und eine Ziffer)";
            } else {
                $stmt = $mysqli->prepare("UPDATE benutzer SET password = ? WHERE ID = ?");
                if (!$stmt) {
                    die("Error: " . $mysqli->error);
                }
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt->bind_param("si", $new_hashed_password, $user_id);
                if (!$stmt->execute()) {
                    die("Error: " . $stmt->error);
                } else {
                    // Password changed successfully
                    $success = "Password changed successfully.";
                }
            }
        } else {
            // Old password is incorrect, display error message
            $error = "The old password is incorrect. Please try again.";
        }
    } else {
        // User ID is not valid, redirect to login page
        header("Location: index.php");
        die();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1 class="text-center">Change Password</h1>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (isset($success)) : ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="form-group">
                                <label for="old_password">Old Password:</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Change Password</button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <a href="myaccount.php" class="btn btn-link">Go back</a>
                        <a href="logout.php" class="btn btn-link">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper-base.min.js" integrity="sha384-+JZV5yjzJQ5L1zv...
</body>

</html>
