<?php

require_once 'vendor/autoload.php';

session_start();

unset($_SESSION['user_token']);

// init configuration
$clientID = '1015807082602-d0pkrcjocmm1u2j3nr259q8ihfg7lur4.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/OptimiseAnything/index2.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
// $client->addScope("https://www.googleapis.com/auth/plus.login  https://www.googleapis.com/auth/userinfo.email");
$client->addScope("profile");
// $login_url = $client->createAuthUrl();


// Connect to database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "googlelogin";

$conn = mysqli_connect($hostname, $username, $password, $database);

// // 检查连接
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// echo "Connected successfully";
// ?>
