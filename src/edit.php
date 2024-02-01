
<?php
session_start();
include('dbconnector.php');
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] /*|| $_SESSION['userID']*/) {
    // Session not OK, redirect to login
    // End the script
    header("Location: index.php");
    die();
}
if (isset($_POST['submit'])) {
    // Get form data
    $id = $_POST['spielID'];
    $name = $_POST['SpielName'];
    $year = $_POST['SpielJahr'];
    $max_size = 2097152; // 2 MB
            if ($_FILES['Bild']['size'] > $max_size) {
                $error = "The uploaded file is too large. Please upload a file that is less than 2 MB.";
                print_r($error);
            }
            if ($mysqli->errno === 1153) {
                $error = "Error: The uploaded file is too large. Please upload a file that is less than 2MB";
        }
    // Check if a new image was uploaded
    if ($_FILES['Bild']['error'] === UPLOAD_ERR_OK) {
        // Check if the uploaded file is an image
        $imageInfo = getimagesize($_FILES['Bild']['tmp_name']);
        if ($imageInfo === false) {
            die("Error: File is not an image");
        }
        // Read the contents of the uploaded file into a binary string
        $image = file_get_contents($_FILES['Bild']['tmp_name']);
        // Update the game in the database with the new image
        $stmt = $mysqli->prepare("UPDATE spiele SET SpielName = ?, SpielJahr = ?, Bild = ? WHERE spielID = ?");
        $stmt->bind_param("sssi", $name, $year, $image, $id);
        $stmt->execute();
        if ($stmt->error) {
            die("Error: " . $stmt->error);
        }
    } else {
        // Update the game in the database without changing the image
        $stmt = $mysqli->prepare("UPDATE spiele SET SpielName = ?, SpielJahr = ? WHERE spielID = ?");
        $stmt->bind_param("ssi", $name, $year, $id);
        $stmt->execute();
        if ($stmt->error) {
            die("Error: " . $stmt->error);
        }
    }
    header("Location: mygames.php");
    exit();
}
$id = $_GET['id'];
$stmt = $mysqli->prepare("SELECT * FROM spiele WHERE spielID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$game = $result->fetch_assoc();
if ($result === false) {
    die("Error: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Game</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<button class="btn btn-primary" onclick="location.href='mygames.php'">Go Back</button>
    <div class="container">
        <h1>Edit Game</h1>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="spielID" value="<?php echo $game['spielID']; ?>">
            <div class="form-group">
                <label for="SpielName">Spielname:</label>
                <input type="text" name="SpielName" class="form-control" value="<?php echo $game['SpielName']; ?>">
            </div>
            <div class="form-group">
                <label for="SpielJahr">Spieljahr:</label>
                <input type="text" name="SpielJahr" class="form-control" value="<?php echo $game['SpielJahr']; ?>">
            </div>
            <div class="form-group">
                <label for="Bild">Bild:</label>
                <input type="file" name="Bild" class="form-control">
                <img src="<?php echo 'data:image/jpeg;base64,' . base64_encode($game['Bild']); ?>" class="img-thumbnail" width="100"><br>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Speichern</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-+KZv3Qz5jyvzK9W8z9J8zjvz8+J5yKQjzJ5zJ6YzJ5zJ6YzJ5zJ6YzJ5zJ6YzJ5z" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>