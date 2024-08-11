<?php
session_start();
require_once 'config.php';

// if (isset($_SESSION['user_token'])) {
//   header("Location: index2.php");
// } else {
//     $showGoogleLogin = true;
// //   echo "<a href='" . $client->createAuthUrl() . "'>Google Login</a>";
// }
?>

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
        <h2 class="text-center">Please read the form carefully</h2>

        <h2 class="text-center">In this task:</h2>
        <h2 class="text-center">1. You need to perform an optimization task through using the service "optimize anything"  </h2>
        <h2 class="text-center"> 2. Answer the questionnaire after the task is completed. </h2>

        <div class="row justify-content-center">
            <div class="col-12">
                <iframe src="InformedConsentForm (2).pdf" class="pdf-viewer" frameborder="0"></iframe>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 text-center mt-3">
                <div class="form-check">
                    <p>Please select one of below options:</p>
                    <input class="form-check-input" type="checkbox" id="option1">
                    <label class="form-check-label" for="option1">
                        I agree to releasing anonymized extracts from my data.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option2">
                    <label class="form-check-label" for="option2">
                        I agree to releasing anonymized extracts from my data only if I am informed about the research groups in question. I have been told what that subset will be.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option3">
                    <label class="form-check-label" for="option3">
                        I do not agree to releasing extracts from my data.
                    </label>
                </div>
                <div class="form-check mt-3">
                    <p>Please select one of options:</p>
                    <input class="form-check-input" type="checkbox" id="option4">
                    <label class="form-check-label" for="option4">
                        I agree to anonymized quotation/publication of extracts from my interview/questionnaires.
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="option5">
                    <label class="form-check-label" for="option5">
                        I do not agree to quotation/publication of extracts from my interview/questionnaires.
                    </label>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="option6">
                    <label class="form-check-label" for="option6">
                        I confirm my participation in this study and agree to volunteer as a study subject.
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
        window.onload = function() {
            localStorage.clear();
        };

        // Function to check if the Start button should be enabled or disabled
        function updateStartButtonState() {
            const option3 = document.getElementById('option3').checked;
            const option5 = document.getElementById('option5').checked;
            const option6 = document.getElementById('option6').checked;

            const startButton = document.getElementById('startButton');

            if (option3 || option5 || option6) {
                startButton.disabled = true;
            } else {
                startButton.disabled = false;
            }
        }

        // Event listeners for checkboxes
        document.getElementById('option1').addEventListener('change', updateStartButtonState);
        document.getElementById('option2').addEventListener('change', updateStartButtonState);
        document.getElementById('option3').addEventListener('change', updateStartButtonState);
        document.getElementById('option4').addEventListener('change', updateStartButtonState);
        document.getElementById('option5').addEventListener('change', updateStartButtonState);
        document.getElementById('option6').addEventListener('change', updateStartButtonState);

        // Navigate to the next page when the start button is clicked
        document.getElementById('startButton').addEventListener('click', function() {
            window.location.href = 'index_id.php';
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
