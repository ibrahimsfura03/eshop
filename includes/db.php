<?php
$servername = "localhost:3307";
$username = "root";
$password = "8569IBB";
$database = "eshop";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
