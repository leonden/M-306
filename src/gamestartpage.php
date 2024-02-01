<?php
  session_start();
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
  <title>Game Collection</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <style>
    .game-info {
      margin-bottom: 0;
    }
  </style>
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
          <li class="active"><a href="gamestartpage.php">Spiele</a></li>
          <li><a href="mygames.php">Meine Spiele</a></li>
          <li><a href="myaccount.php">Mein Konto</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>

  <div class="container">
    <?php
    // Connect to the database
    include('dbconnector.php');

    // Retrieve the game data from the database
    $stmt = $mysqli->query("SELECT s.Bild, s.SpielName, s.SpielJahr, b.username
    FROM spiele s
    INNER JOIN benutzer b ON s.benutzerFK = b.ID");
    $games = $stmt->fetch_all(MYSQLI_ASSOC);

    // Display the game data
    foreach ($games as $game) {
    ?>
      <div class="row">
        <div class="col-md-4">
          <?php
          $image_data = base64_encode($game['Bild']);
          $image_src = 'data:image/png;base64,' . $image_data;
          ?>
          <img src="<?php echo $image_src; ?>" alt="<?php echo $game['SpielName']; ?>" class="img-responsive" width = "100">
        </div>
        <div class="col-md-8">
          <h2><?php echo $game['SpielName']; ?></h2>
          <p class="game-info">Veröffentlichungsjahr: <?php echo $game['SpielJahr']; ?></p>
          <p class="game-info">Besitzer des Spiels: <?php echo $game['username']; ?></p>
        </div>
      </div>
      <hr>
    <?php
    }
    ?>
  </div>

  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>

</html>
