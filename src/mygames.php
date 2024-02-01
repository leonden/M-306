<?php
session_start();
include('dbconnector.php');
if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
  // Session nicht OK,  Weiterleitung auf Anmeldung
  //  Script beenden
  header("Location: index.php");
  die();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MyGames</title>


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
  <!-- Navigation bar -->
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
          <li class="active"><a href="mygames.php">Meine Spiele</a></li>
          <li><a href="myaccount.php">Mein Konto</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="addgame.php"><span class="glyphicon glyphicon-plus"></span> Add Game</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <!-- Game list -->
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2>My Games</h2>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Spieltitel</th>
              <th>Veröffentlichungsjahr</th>
              <th>Cover</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Retrieve the user's game data from the database
            $stmt = $mysqli->prepare("SELECT * FROM spiele WHERE benutzerFK = ?");
            $stmt->bind_param("i", $_SESSION['userID']);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the query returned any results
            if ($result === false) {
              die("Error: " . $mysqli->error);
            }
            while ($row = $result->fetch_assoc()) {
            ?>
              <tr>
                <td><?php echo $row['SpielName']; ?></td>
                <td><?php echo $row['SpielJahr']; ?></td>
                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['Bild']); ?>" width="100"></td>
                <td>
                <td>
                  <form action="edit.php" method="get">
                    <input type="hidden" name="id" value=<?php echo $row['spielID']; ?>>
                    <button type="submit">Edit</button>
                  </form>
                  &nbsp;&nbsp;&nbsp;
                  <form action="delete.php" method="get" onsubmit="return confirm('Are you sure you want to delete this game?');">
                    <input type="hidden" name="id" value=<?php echo $row['spielID']; ?>>
                    <button type="submit">Delete</button>
                  </form>


                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>

</html>