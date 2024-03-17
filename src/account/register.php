<?php
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $password = $_POST['password'];

    $conn = new mysqli("localhost", "root", "", "taskmaster");
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO user (username, firstname, lastname, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $firstname, $lastname, $hashed_password);
    if($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <?php if(isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" required><br>
        First Name: <input type="text" name="firstname" required><br>
        Last Name: <input type="text" name="lastname" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
