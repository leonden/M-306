<?php
session_start();
if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
    // Session not OK, redirect to login
    header("Location: index.php");
    die();
}
include('dbconnector.php'); // Make sure this file is included
// Initialize variables
$message = "";
$error = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    

    // Check if all required fields are filled
    $spielname = trim($_POST['spielname']);
    $spieljahr = trim($_POST['spieljahr']);

    if (empty($spielname) || empty($spieljahr)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if a file was uploaded
        if (isset($_FILES['bild']) && !empty($_FILES['bild']['tmp_name'])) {
            $bild = file_get_contents($_FILES['bild']['tmp_name']);

            // Check if the uploaded file is within the allowed size limit (2 MB)
            $max_size = 2097152; // 2 MB
            if ($_FILES['bild']['size'] > $max_size) {
                $error = "The uploaded file is too large. Please upload a file that is less than 2 MB.";
                
            }
        } else {
            $error = "Please upload an image.";
        }

        if (empty($error)) {
            // SQL query to insert data into the 'spiele' table using prepared statement
            $sql = "INSERT INTO spiele (spielname, spieljahr, Bild, benutzerFK) VALUES (?, ?, ?, ?)";

            // Prepare the SQL statement
            if ($stmt = $mysqli->prepare($sql)) {
                // Bind the parameters
                $stmt->bind_param("ssbs", $spielname, $spieljahr, $bild, $_SESSION['userID']);
                $stmt->send_long_data(2, $bild);

                // Execute the statement
                if ($stmt->execute()) {
                    $message = "Data has been successfully inserted into the database.";
                    header("Location: mygames.php");
                } else {
                    $error = "Error inserting data into the database: " . $stmt->error;
                    if ($mysqli->errno === 1153) {
                        $error = "Error: The uploaded file is too large. Please upload a file that is less than 2MB";
                }
                $stmt->close();
            }} else {
                $error = "Error preparing the SQL statement: " . $mysqli->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Game</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
    <!-- Navigation bar -->
    <nav>
        <ul>
            <button type="button" class="btn btn-default"><a href="mygames.php">Go back</a></button>
        </ul>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Add Game</h2>
                <?php
                if (!empty($error)) {
                    echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
                } elseif (!empty($message)) {
                    echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
                }
                ?>
                <form action="addgame.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="spielname" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Publication Year:</label>
                        <input type="number" class="form-control" id="year" name="spieljahr" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control" id="image" name="bild" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Game</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>