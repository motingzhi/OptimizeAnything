<?php
$hostname = "localhost";
$username = "root";
$password = "12345678";
$dbname = "optimize1";

$conn = new mysqli($hostname, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
