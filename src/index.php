<?php
//Session starten
session_start();
//Datenbankverbindung
include('dbconnector.php');

$error = '';
$message = '';


// Formular wurde gesendet und Besucher ist noch nicht angemeldet.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	echo "<pre>";
	//print_r($_POST);
	echo "</pre>";
	// username
	if (isset($_POST['username'])) {
		//trim
		$username = trim($_POST['username']);

		// prüfung benutzername
		if (empty($username)) {
			$error .= "Der Benutzername entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte den Benutzername an.<br />";
	}
	// password
	if (isset($_POST['password'])) {
		//trim
		$password = trim($_POST['password']);
		// passwort gültig?
		if (empty($password)) {
			$error .= "Das Passwort entspricht nicht dem geforderten Format.<br />";
		}
	} else {
		$error .= "Geben Sie bitte das Passwort an.<br />";
	}

	// kein fehler
	if (empty($error)) {
		// SQL query to retrieve user data based on username
		$sql = "SELECT id, username, password FROM benutzer WHERE username = ?";

		// Prepare the SQL statement
		if ($stmt = $mysqli->prepare($sql)) {
			// Bind the parameter
			$stmt->bind_param("s", $username);
			$stmt->execute();

			$result = $stmt->get_result();

			if ($result->num_rows) {

				while ($row = $result->fetch_assoc()) {
					// Verify he password
					if (password_verify($password, $row['password'])) {
						// Password is correct
						$message .= "Sie sind nun eingeloggt.<br>";
						$_SESSION['loggedin'] = true;
						$_SESSION['userID'] = $row['id'];
						session_regenerate_id(true);
						header("Location: gamestartpage.php");
						exit; // Make sure to exit to prevent further script execution

					} else {
						$error .= "Benutzername oder Passwort sind falsch.";
					}
				}
			} else {
				$error .= "Benutzername oder Passwort sind falsch.";
			}
			// Close the statement
			$stmt->close();
		} else {
			$error .= "Fehler beim Vorbereiten des SQL-Statements: " . $mysqli->error;
		}
	}
}
// Successful login



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

	<!-- Bootstrap -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Login</h3>
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
								<label for="username">Benutzername</label>
								<input type="text" name="username" class="form-control" id="username" value="" title="Bitte gebe deinen Benutzernamen ein." maxlength="50" required="true">
							</div>
							<!-- password -->
							<div class="form-group">
								<label for="password">Passwort</label>
								<input type="password" name="password" class="form-control" id="password" placeholder="Bitte geben Sie ihr Passwort ein." maxlength="255" required="true">
							</div>
							<button type="submit" name="submit" value="submit" class="btn btn-primary btn-block">Anmelden</button>
							<a href=register.php class="btn btn-default btn-block">Registrieren</a>
						</form>
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