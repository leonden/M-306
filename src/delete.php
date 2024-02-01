<?php
session_start();
include('dbconnector.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) or !$_SESSION['loggedin']) {
    header("Location: index.php");
    session_destroy();
    die();
    
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

// Check if the user is authorized for this game
if ($game['benutzerFK'] != $_SESSION['userID']) {
    // User is not authorized, redirect to login
    // End the script
    header("Location: index.php");
    session_destroy();
    die();

}

// Check if the 'id' parameter is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $gameId = $_GET['id'];

    

    // Prepare the DELETE statement
    $stmt = $mysqli->prepare("DELETE FROM spiele WHERE SpielID = ?");
    $stmt->bind_param("i", $gameId);

    // Execute the DELETE statement
    if ($stmt->execute()) {
        // Deletion was successful; you can redirect the user or show a success message.
        header("Location: mygames.php");
        die();
    } else {
        // An error occurred during deletion; you can show an error message or handle it as needed.
        echo "Error deleting the game: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // 'id' parameter is missing or invalid; handle the error (e.g., show an error message).
    echo "Invalid or missing 'id' parameter.";
}
