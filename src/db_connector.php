<?php
$host = "172.31.7.98";
$username = "taskmaster";
$password = "taskmaster";
$database = "taskmaster";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
