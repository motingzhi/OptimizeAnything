<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prolificID = $_POST['Prolific'];
    $solutionlist = ''; // 默认值
    $savedsolutions = ''; // 默认值
    $savedobjectives = ''; // 默认值
    $parameterNames = ''; // 默认值
    $parameterBounds = '';
    $defineTimestamp = '';

    if (empty($prolificID)) {
        die("Prolific ID is required");
    }

    $stmt = $conn->prepare("INSERT INTO data (ID, Solutionlist, Savedsolutions, Savedobjectives, parametername, parameterbounds, definetimestamp ) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $prolificID, $solutionlist, $savedsolutions, $savedobjectives, $parameterNames, $parameterBounds, $defineTimestamp);
    if ($stmt->execute()) {
        $_SESSION['ProlificID'] = $prolificID; // 存储 Prolific ID 到会话中
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .top-bar {
            margin-top: 120px;
        }
        .top-bar h1 {
            font-size: 36px;
        }
        .subheading {
            margin-top: 80px;
            font-size: 18px;
        }
        .image-section {
            margin-top: 80px;
        }
        .centered-content img {
            max-width: 100%;
        }
        .content-description {
            margin-top: 120px;
            font-size: 18px;
        }
        .card-section {
            margin-top: 80px;
            margin-bottom: 40px;
        }
        .fixed-size-card {
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
        .card-title {
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="container text-center top-bar">
        <h1>Optimize Anything!</h1>
        <p class="subheading">This service will help you to make the best decision with AI.<br>Here is an example on how it works:</p>
    </div>

    <div class="container text-center centered-content image-section">
        <img src="Pictures/image 9.png" alt="Example Process">
    </div>

    <div class="container text-center content-description">
        <p>To start, select an example that interests you to go through the tutorial first.</p>
    </div>

    <div class="container card-section">
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Build a rocket</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <a href="tutorial_1.php" class="card-link">
                    <div class="card fixed-size-card">
                        <div class="card-body">
                            <h4 class="card-title">Optimize car material</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card fixed-size-card">
                    <div class="card-body">
                        <h4 class="card-title">Plan a trip</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poTQDC+9m28p4yp0I6i51m8bo7A9oKNV7KLD3yoaz9zT0E4no5Z" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js" integrity="sha384-pzjw8f+ua7Kw1TIqic4YVOuVVV1F6wJ4g2KqLkEBwJB0+TE9YfIWqZl0O2VSr10p" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"></script>
</body>
</html>



