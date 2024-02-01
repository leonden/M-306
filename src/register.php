<?php

// Initialisierung
$error = '';
$firstname = $lastname = $username = $password = '';

//Datenbankverbindung
include('dbconnector.php');

// Wurden Daten mit "POST" gesendet?
if ($_SERVER['REQUEST_METHOD'] == "POST") {

  // Validate input fields for non-empty values
  $firstname = trim($_POST['firstname']);
  $lastname = trim($_POST['lastname']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $message = "";
  // Hash the password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  /** TODO 
   * alle Benutzereingaben gemäss Auftrag validieren
   * Variable vorhanden
   * nicht leer
   * minimale Länge
   * maximale Länge
   * 
   * sonst Fehlermeldung an Variable $error anhängen.
   * $error .= "Geben Sie bitte einen korrekten Vornamen ein";
   */

  if (empty($firstname) || strlen($firstname) >= 20) {
    $error .= "Geben Sie bitte einen korrekten Vornamen ein. (Maximal 30 Zeichen)";
  }

  if (empty($lastname) || strlen($lastname) >= 30) {
    $error .= "Geben Sie bitte einen korrekten Nachnamen ein. (Maximal 30 Zeichen)";
  }

  if (empty($username) || strlen($username) >= 30) {
    $error .= "Geben Sie bitte einen korrekten Benutzernamen ein. (Maximal 30 Zeichen)";
  }

  // Check if the password meets the requirements
  if (strlen($password) < 8) {
    $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
  } elseif (!preg_match('/[A-Z]/', $password)) {
    $error = "Das Passwort muss mindestens einen Großbuchstaben enthalten.";
  } elseif (!preg_match('/[a-z]/', $password)) {
    $error = "Das Passwort muss mindestens einen Kleinbuchstaben enthalten.";
  } elseif (!preg_match('/[0-9]/', $password)) {
    $error = "Das Passwort muss mindestens eine Ziffer enthalten.";
  }
  $check_query = "SELECT username FROM benutzer WHERE username = ?";

  if ($stmt = $mysqli->prepare($check_query)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "Benutzername bereits vergeben. Bitte wählen Sie einen anderen Benutzernamen.";
    } else {
      // User with the same username doesn't exist, proceed with the insertion.
      $stmt->close();
      // SQL query to insert data into the 'benutzer' table using prepared statement
      $sql = "INSERT INTO benutzer (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";

      // Prepare the SQL statement
      if ($stmt = $mysqli->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $hashed_password);

        // Execute the statement
        if ($stmt->execute()) {
          $message = "Daten wurden erfolgreich in die Datenbank eingefügt.";
        } else {
          $error = "Fehler beim Einfügen der Daten in die Datenbank: " . $stmt->error;
        }
        header("Location: index.php");
        // Close the statement
        $stmt->close();
      } else {
        $error = "Fehler beim Vorbereiten des SQL-Statements: " . $mysqli->error;
      }
    }
  }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrierung</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Registrierung</h3>
          </div>
          <div class="panel-body">
            <?php
            // fehlermeldung oder nachricht ausgeben
            if (!empty($message)) {
              echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
            }
            if (!empty($error)) {
              echo "<div class=\"alert alert-danger\" role=\"alert\">" . $error . "</div>";
            }

            ?>
            <form action="" method="POST">
              <div class="form-group">
                <label for="firstname">Vorname</label>
                <input type="text" name="firstname" class="form-control" id="firstname" value="<?php echo $firstname; ?>" title="Bitte gebe deinen Vornamen ein." maxlength="30" required="true">
              </div>
              <div class="form-group">
                <label for="lastname">Nachname</label>
                <input type="text" name="lastname" class="form-control" id="lastname" value="<?php echo $lastname; ?>" title="Bitte gebe deinen Nachnamen ein." maxlength="30" required="true">
              </div>

              <div class="form-group">
                <label for="username">Benutzername</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php echo $username; ?>" title="Bitte gebe deinen Benutzernamen ein." maxlength="50" required="true">
              </div>
              <div class="form-group">
                <label for="password">Passwort</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Bitte gib dein Passwort ein." maxlength="255" required="true">
              </div>
              <button type="submit" name="submit" value="submit" class="btn btn-primary btn-block">Registrieren</button>
              <a href=index.php class="btn btn-default btn-block">Zurück zum Login</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>

</html>