
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
//   $client->getCache()->clear();
    if(isset($token["error"]) && ($token["error"] == "invalid_grant")){
        header("Location: index.php");
    }
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $userinfo = [
    'email' => $google_account_info['email'],
    'first_name' => $google_account_info['givenName'],
    'last_name' => $google_account_info['familyName'],
    'gender' => $google_account_info['gender'],
    'full_name' => $google_account_info['name'],
    'picture' => $google_account_info['picture'],
    'verifiedEmail' => $google_account_info['verifiedEmail'],
    'token' => $google_account_info['id'],
  ];

  // // checking if user is already exists in database
  // $sql = "SELECT * FROM users WHERE email ='{$userinfo['email']}'";
  // $result = mysqli_query($conn, $sql);
  // if (mysqli_num_rows($result) > 0) {
  //   // user is exists
  //   $userinfo = mysqli_fetch_assoc($result);
  //   $token = $userinfo['token'];
  // } else {
  //   // user is not exists
  //   $sql = "INSERT INTO users (email, first_name, last_name, gender, full_name, picture, verifiedEmail, token) VALUES ('{$userinfo['email']}', '{$userinfo['first_name']}', '{$userinfo['last_name']}', '{$userinfo['gender']}', '{$userinfo['full_name']}', '{$userinfo['picture']}', '{$userinfo['verifiedEmail']}', '{$userinfo['token']}')";
  //   $result = mysqli_query($conn, $sql);
  //   if ($result) {
  //     $token = $userinfo['token'];
  //   } else {
  //     echo "User is not created";
  //     die();
  //   }
  // }

  // save user data into session
  $_SESSION['user_token'] = $token;
} else {
  if (!isset($_SESSION['user_token'])) {
    header("Location: index.php");
    die();
  }


  // // checking if user is already exists in database
  // $sql = "SELECT * FROM users WHERE token ='{$_SESSION['user_token']}'";
  // $result = mysqli_query($conn, $sql);
  // if (mysqli_num_rows($result) > 0) {
  //   // user is exists
  //   $userinfo = mysqli_fetch_assoc($result);
  // }
}

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .top-bar {
            position: fixed;
            top: calc(100vh / 12);
            width: 100%;
            background: transparent;
            padding: 10px 0;
            box-shadow: none;
            z-index: 1000; 
        }
        .centered-content {
            margin-top: calc(100vh / 10 + 100px); /* Offset by the height of top-bar */
            text-align: center;
            width: 33.33%; /* Content width as 1/3 of the page */
            margin-left: auto;
            margin-right: auto;
        }
        .text-left-align {
            text-align: left; /* Ensure text within this div is left-aligned */
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="container text-center">
            <h1>Optimize Anything!</h1>
        </div>
    </div>

    <div class="centered-content">
            <p>Let AI help you find the best solution</p>
            <p><b>Three steps:</b></p>
            <div class="text-left-align">

                <ol>
                    <li><b>Specify. </b> Tell us what you want to optimize (5 mins)</li>
                    <li><b>Optimize. </b> Let AI help you find the best alternatives. (Stop when you want.)</li>
                    <li><b>Results. </b>We'll present you the best alternatives with their tradeoffs.</li>
                </ol>
                <br>
            </div>

        <div style="text-align: center;">
    <img src="<?= $userinfo['picture'] ?>" alt="" width="90px" height="90px" class="round-img">
    <ul>
        <li>Full Name: <?= $userinfo['full_name'] ?></li>
        <li>Email Address: <?= $userinfo['email'] ?></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    </div>

    <div style="text-align: center;">
        <form action="how-it-works.php">
            <input type="submit" value="Let's start!" class="btn btn-success" />
        </form>
    </div>
    
    </div>
      </div>

      </div>

</body>
</html>
    
