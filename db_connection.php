<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "c3e";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, you can set the charset for proper character encoding
$conn->set_charset("utf8mb4");

?>
