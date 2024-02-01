
<?php
// Variabeln deklarieren
$host = 'localhost'; // host
$dbuser = '151_data_user'; // username
$password = 'bbzbl12345'; // password
$database = '151_data'; // database



// mit der Datenbank verbinden
$mysqli = new mysqli($host, $dbuser, $password, $database);



// Fehlermeldung, falls Verbindung fehl schlägt.
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
?>