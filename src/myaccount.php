<?php
include('dbconnector.php'); // Make sure this file is included
session_start();
      if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
        // Session nicht OK,  Weiterleitung auf Anmeldung
        //  Script beenden
        header("Location: index.php");
        die();
    }

// Retrieve the user's data from the database
$userID = $_SESSION['userID'];
$stmt = $mysqli->prepare("SELECT * FROM benutzer WHERE ID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($result === false) {
    die("Error: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Account</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Game Collection</a>
      </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li><a href="gamestartpage.php">Spiele</a></li>
          <li><a href="mygames.php">Meine Spiele</a></li>
          <li class="active"><a href="myaccount.php">Mein Konto</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

  <div class="container">
    <h1>My Account</h1>
    <p><strong>Vorname:</strong> <?php echo $user['firstname']; ?></p>
    <p><strong>Nachname:</strong> <?php echo $user['lastname']; ?></p>
    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
    <p><strong>Password:</strong> ********</p>
    <a href="changepassword.php" class="btn btn-primary">Change Password</a>
  </div>
</body>
</html>