<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['ProlificID'])) {
    // 如果会话中没有 Prolific ID，则重定向到初始页面
    header("Location: index.php");
    exit();
}
$userID = $_SESSION['ProlificID']; // 从会话中获取用户 ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datetime = new DateTime("now", new DateTimeZone("Europe/Helsinki"));
    $tutorial_timestamp = json_encode($datetime->format("Y-m-d H:i:s")); // 格式化时间戳为字符串
  

    $stmt = $conn->prepare("UPDATE data SET tutorial_timestamp = ?  WHERE prolific_ID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $tutorial_timestamp,$userID);
    if ($stmt->execute()) {
        echo "Record updated successfully";
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
    <title>Check</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <style>
        .disabled {
            pointer-events: none;
            opacity: 0.5;
        }
        .correct {
            color: green;
            font-weight: bold;
        }
        .incorrect {
            color: red;
            font-weight: bold;
        }
        .submit-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Comprehension Check</h1>
        <h3>1. You have two chances to answer each question. You need to click the "Submit" button to submit the answer for each question.</h3>
        <h3>2. Each question is a <strong>multiple-choice </strong>question. There may be one or two answers.
           </h3>
        <h3>3. You can go back to the tutorial to find the answers.</h3>

<br><br>
        <!-- Question 1 -->
        <div id="question1">
            <h3>1. Which statements are <strong>NOT</strong> correct based on the tutorials of the "Specify" and the "Optimize" step?<br> Select all incorrect options.</h3>
            <p>(See the pictures below)</p>
            <form>
                <div>
                    <img src="Pictures/Group 142.png" alt="Specify objectives" class="img-fluid" style="  width: 40%"><br>

                    <input type="checkbox" id="q1_opt1" value="1"> 
                    <label for="q1_opt1" style="  width: 70%">⬆️The "Variables" I specified on page 1 should be the same as the "Objectives" I specified on page 2.</label><br><br>
                </div>
                <div>
                <img src="Pictures/Group 144.png" alt="Specify objectives" class="img-fluid" style="  width: 40%"><br>

                    <input type="checkbox" id="q1_opt2" value="0" > 
                    <label for="q1_opt2" style="  width: 70%">⬆️The values of a solution must be within the minimum and maximum ranges of “Variables” specified by the user.</label>
                </div>
                <button type="button" class="btn btn-primary submit-btn" id="q1_submit">Submit</button>
                <p id="q1_feedback"></p>
            </form>
        </div>

        <!-- Question 2 -->
        <div id="question2" class="mt-4">
            <h3>2. Which statements are <strong>NOT</strong> correct based on the tutorial of the "Optimize" step?<br>Select all incorrect options.</h3>
            <p>(The attached images are user's screenshots in the optimization step)</p>
            <form>
                <div>
                <img src="Pictures/Group 146.png" alt="Specify objectives" class="img-fluid" style="  width: 40%"><br>

                    <input type="checkbox" id="q2_opt1" value="1"> 
                    <label for="q2_opt1" style="  width: 70%">⬆️The user should copy the values for the new solution into the "Measurements" column.</label><br><br>
                </div>
                <div>
                <img src="Pictures/Group 143.png" alt="Specify objectives" class="img-fluid" style="  width: 40%"><br>

                    <input type="checkbox" id="q2_opt2" value="1"> 
                    <label for="q2_opt2" style="  width: 70%">⬆️The first objective, "Material cost", should only be used to evaluate the first variable, "Material strength", and the second objective, "Material durability", should only be used to evaluate the second variable, "Material density".</label>
                </div>
                <button type="button" class="btn btn-primary submit-btn" id="q2_submit">Submit</button>
                <p id="q2_feedback"></p>
            </form>
        </div>

        <!-- Next Button -->
        <div class="text-center mt-5">
            <button id="next_btn" class="btn btn-success disabled">Next</button>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        // Retrieve stored quiz data from localStorage
        let quizData = JSON.parse(localStorage.getItem('quizData')) || {
            q1_attempts: 0,
            q1_correct: false,
            q2_attempts: 0,
            q2_correct: false,
        };

        // Apply stored quiz data on page load
        document.addEventListener("DOMContentLoaded", function () {
            if (quizData.q1_correct || quizData.q1_attempts >= 2) {
                disableQuestion(1);
                displayFeedback(1, quizData.q1_correct);
            }
            if (quizData.q2_correct || quizData.q2_attempts >= 2) {
                disableQuestion(2);
                displayFeedback(2, quizData.q2_correct);
            }
            checkNextButton();
        });

        // Question 1 logic
        document.getElementById('q1_submit').addEventListener('click', function () {
            quizData.q1_attempts++;
            const q1_opt1 = document.getElementById('q1_opt1').checked;
            const q1_opt2 = document.getElementById('q1_opt2').checked;

            if (q1_opt1 && !q1_opt2) {
                quizData.q1_correct = true;
                displayFeedback(1, true);
                disableQuestion(1);
            } else {
                if (quizData.q1_attempts >= 2) {
                    displayFeedback(1, false);
                    disableQuestion(1);
                } else {
                    displayFeedback(1, false);
                }
            }

            // Store the updated quiz data
            localStorage.setItem('quizData', JSON.stringify(quizData));
            checkNextButton();
        });

        // Question 2 logic
        document.getElementById('q2_submit').addEventListener('click', function () {
            quizData.q2_attempts++;
            const q2_opt1 = document.getElementById('q2_opt1').checked;
            const q2_opt2 = document.getElementById('q2_opt2').checked;

            if (q2_opt1 && q2_opt2) {
                quizData.q2_correct = true;
                displayFeedback(2, true);
                disableQuestion(2);
            } else {
                if (quizData.q2_attempts >= 2) {
                    displayFeedback(2, false);
                    disableQuestion(2);
                } else {
                    displayFeedback(2, false);
                }
            }

            // Store the updated quiz data
            localStorage.setItem('quizData', JSON.stringify(quizData));
            checkNextButton();
        });

        // Disable question after two attempts or correct answer
        function disableQuestion(questionNumber) {
            const form = document.querySelector(`#question${questionNumber} form`);
            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => input.disabled = true);
            const submitButton = form.querySelector('button');
            submitButton.disabled = true;
        }

        // Display feedback based on answer correctness
        function displayFeedback(questionNumber, isCorrect) {
            const feedback = document.getElementById(`q${questionNumber}_feedback`);
            if (isCorrect) {
                feedback.textContent = "Correct!";
                feedback.className = "correct";
            } else if (quizData[`q${questionNumber}_attempts`] >= 2) {
                feedback.textContent = "Incorrect! No more attempts.";
                feedback.className = "incorrect";
            } else {
                feedback.textContent = "Incorrect! Try again.";
                feedback.className = "incorrect";
            }
        }

        // Enable the "Next" button if conditions are met
        function checkNextButton() {
            if ((quizData.q1_correct || quizData.q1_attempts >= 2) && (quizData.q2_correct || quizData.q2_attempts >= 2)) {
                document.getElementById('next_btn').classList.remove('disabled');
            }
        }

        // Handle "Next" button click
        document.getElementById('next_btn').addEventListener('click', function () {
            if (quizData.q1_correct && quizData.q2_correct) {
                window.location.href = 'material_5.php';  // 跳转到网页1
                // var pass = 0;
                // localStorage.setItem('pass', pass);
                
                // $.ajax({
                //         url: "page2.php",
                //         type: "post",
                //         data: {
                //             // 'ismanual'   :ismanual,
                //             'pass'   :pass
                            

                //         },
                //         success: function(response) {
                //             window.location.href = 'page2.php';  // 跳转到网页2
                //         },
                //         error: function(xhr, status, error) {
                //             console.error('AJAX Error: ' + status + error);
                //         }
                //     });

            } else {
                var pass = 0;
                localStorage.setItem('pass', pass);
                
                $.ajax({
                        url: "page2.php",
                        type: "post",
                        data: {
                            // 'ismanual'   :ismanual,
                            'pass'   :pass
                            

                        },
                        success: function(response) {
                            window.location.href = 'page2.php';  // 跳转到网页2
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + error);
                        }
                    });

                // window.location.href = 'page2.php';  // 跳转到网页2
            }
        });
    </script>
</body>
</html>
