<?php
session_start();

// Check if user is already logged in, if yes, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to TaskMaster</h1>
        <p>Please <a href="./account/login.php">login</a> or <a href="./account/register.php">register</a> to continue.</p>
    </div>
</body>
</html>
