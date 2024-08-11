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
                <iframe src="InformedConsentForm (3).pdf" class="pdf-viewer" frameborder="0"></iframe>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 text-left mt-3">
                <div class="form-check">
                    <p><strong>1. You understand that a fully anonymized subset of the data may be released to other research groups for the purposes mentioned above, if you give permission to it. </strong></p>
                    <p>Please select one of below options</p>

                    <input class="form-check-input" type="radio" name="dataRelease" id="agreeRelease" value="agreeRelease">
                    <label class="form-check-label" for="agreeRelease">
                        I agree to releasing anonymized extracts from my data.
                    </label><br>
                    <input class="form-check-input" type="radio" name="dataRelease" id="conditionalRelease" value="conditionalRelease">
                    <label class="form-check-label" for="conditionalRelease">
                        I agree to releasing anonymized extracts from my data only if I am informed about the research groups in question. I have been told what that subset will be.
                    </label><br>
                    <input class="form-check-input" type="radio" name="dataRelease" id="disagreeRelease" value="disagreeRelease">
                    <label class="form-check-label" for="disagreeRelease">
                        I do not agree to releasing extracts from my data.
                    </label>
                </div>

                <div class="form-check mt-3">
                    <p><strong>2. You understand that extracts from possible interviews/questionnaires may be quoted in subsequent publications, and you agree to anonymized quotation/publication of extracts from the interview/ questionnaires.</strong></p>
                    <input class="form-check-input" type="radio" name="quotationPermission" id="agreeQuotation" value="agreeQuotation">
                    <label class="form-check-label" for="agreeQuotation">
                         I understand and agree.
                    </label><br>
                    <input class="form-check-input" type="radio" name="quotationPermission" id="disagreeQuotation" value="disagreeQuotation">
                    <label class="form-check-label" for="disagreeQuotation">
                        I do not agree.
                    </label>
                </div>

                <div class="form-check mt-3">
                    <p><strong>3. By selecting this option, you agree to all other terms in the PDF consent form displayed on this web, confirm your participation in this study and agree to volunteer as a study subject.</strong></p>
                    <input class="form-check-input" type="checkbox" id="confirmParticipation">
                    <label class="form-check-label" for="confirmParticipation">
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

        const startButton = document.getElementById('startButton');
        const confirmParticipation = document.getElementById('confirmParticipation');

        const dataReleaseOptions = document.getElementsByName('dataRelease');
        const quotationOptions = document.getElementsByName('quotationPermission');

        function checkConsent() {
            const selectedDataRelease = Array.from(dataReleaseOptions).find(option => option.checked);
            const selectedQuotation = Array.from(quotationOptions).find(option => option.checked);

            if (selectedDataRelease && selectedQuotation && confirmParticipation.checked) {
                if (
                    selectedDataRelease.id !== 'disagreeRelease' &&
                    selectedQuotation.id !== 'disagreeQuotation'
                ) {
                    startButton.disabled = false;
                } else {
                    startButton.disabled = true;
                }
            } else {
                startButton.disabled = true;
            }
        }

        dataReleaseOptions.forEach(option => option.addEventListener('change', checkConsent));
        quotationOptions.forEach(option => option.addEventListener('change', checkConsent));
        confirmParticipation.addEventListener('change', checkConsent);

        document.getElementById('startButton').addEventListener('click', function() {
            window.location.href = 'index_id.php';  // 跳转到index_id.php页面
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
