<?php
session_start();
require_once 'config.php';

// if (isset($_SESSION['user_token'])) {
//   header("Location: index2.php");
// } else {
//     $showGoogleLogin = true;
// //   echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";
// }
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Consent Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .pdf-viewer {
            width: 100%;
            height: 1000px;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="text-center">User Consent Form</h1>
        <div class="row justify-content-center">
            <div class="col-12">
                <iframe src="InformedConsentForm 1.pdf" class="pdf-viewer" frameborder="0"></iframe>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="consentCheckbox">
                    <label class="form-check-label" for="consentCheckbox">
                        By checking this I give my consent
                    </label>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-3">
                <button id="startButton" class="btn btn-primary" disabled>Start</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('consentCheckbox').addEventListener('change', function() {
            document.getElementById('startButton').disabled = !this.checked;
        });
        document.getElementById('startButton').addEventListener('click', function() {
            window.location.href = 'index_id.php';  // 跳转到index.php页面
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
