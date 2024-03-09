
<?php
$host = 'localhost'; // host
$dbuser = 'taskmaster_dbuser'; // username
$password = 'tm_admin'; // password
$database = 'taskmaster_db_prod'; // database



// Connect to the database
$mysqli = new mysqli($host, $dbuser, $password, $database);



// Check for errors
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
}
?>